<?php

namespace Modules\Curriculums\Http\Controllers\Api\v1;

use App\Http\Controllers\Base\BaseApiController;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Modules\Courses\Entities\Course;
use Modules\Curriculums\Entities\Curriculum;
use Modules\Curriculums\Http\Requests\CreateCurriculumRequest;
use Modules\Curriculums\Http\Requests\GetCurriculumsRequest;
use Modules\Curriculums\Http\Requests\UpdateCurriculumRequest;
use Modules\Curriculums\Policies\CurriculumsStudentsContractPolicy;
use Modules\Curriculums\Services\CurriculumService;
use Modules\Curriculums\Transformers\CurriculumItem;
use Modules\Curriculums\Transformers\CurriculumsCollection;
use Modules\Users\Entities\StudentContract;

class CurriculumApiController extends BaseApiController
{
    /**
     * @OA\Get(
     *  path="/curriculums",
     *  tags={"Curriculums"},
     *  summary="Get curriculum list.",
     *  security={{"bearerAuth":{}}},
     *  @OA\Parameter(
     *      name="page", in="query", required=false,
     *      description="Page of curriculums list.",
     *      @OA\Schema(type="string", default="1")
     *  ),
     *  @OA\Parameter(
     *      name="search", in="query", required=false,
     *      description="Search for the curriculums by name or description. Requirements: min 3 symbols",
     *      @OA\Schema(type="string")
     *  ),
     *  @OA\Parameter(
     *      name="order_by", in="query", required=false,
     *      description="_Allowed fields params:_ **name, start_at, end_at**. _Direction:_ **desc, asc**",
     *      style="deepObject",
     *      @OA\Schema(type="object", example="{'created_at':'asc'}"),
     *  ),
     *  @OA\Response(
     *      response=200, description="Resource created.",
     *      @OA\JsonContent(allOf={
     *           @OA\Schema(ref="#/components/schemas/SuccessResponse"),
     *           @OA\Schema(@OA\Property(property="message", example="Curriculums list.")),
     *           @OA\Schema(@OA\Property(property="data", ref="#/components/schemas/CurriculumsCollection"))
     *      })
     *  ),
     *  @OA\Response(response=400, ref="#/components/responses/ErrorResponse400"),
     *  @OA\Response(response=401, ref="#/components/responses/ErrorResponse401"),
     *  @OA\Response(response=403, ref="#/components/responses/ErrorResponse403"),
     * )
     *
     * @param \Modules\Curriculums\Http\Requests\GetCurriculumsRequest $request
     * @param \Modules\Curriculums\Services\CurriculumService $service
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(GetCurriculumsRequest $request, CurriculumService $service): JsonResponse
    {
        $curriculums = $service->getAll($request->getCurriculumsFiltersDto());

        return $this->sendSuccessResponse(
            __("Curriculums list."),
            new CurriculumsCollection($curriculums)
        );
    }

    /**
     * @OA\Post(
     *  path="/curriculums",
     *  tags={"Curriculums"},
     *  summary="Create new curriculum.",
     *  security={{"bearerAuth":{}}},
     *  requestBody={"$ref": "#/components/requestBodies/CreateCurriculumRequest"},
     *  @OA\Response(
     *      response=201, description="Resource created.",
     *      @OA\JsonContent(allOf={
     *           @OA\Schema(ref="#/components/schemas/SuccessResponse"),
     *           @OA\Schema(@OA\Property(property="message", example="Course created.")),
     *           @OA\Schema(@OA\Property(property="data", ref="#/components/schemas/CurriculumItem"))
     *      })
     *  ),
     *  @OA\Response(response=400, ref="#/components/responses/ErrorResponse400"),
     *  @OA\Response(response=401, ref="#/components/responses/ErrorResponse401"),
     *  @OA\Response(response=403, ref="#/components/responses/ErrorResponse403"),
     * )
     *
     * @param \Modules\Curriculums\Http\Requests\CreateCurriculumRequest $request
     * @param \Modules\Curriculums\Services\CurriculumService $service
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateCurriculumRequest $request, CurriculumService $service): JsonResponse
    {
        $curriculum = $service->create($request->getCurriculumDto());

        return $this->sendSuccessResponse(
            __("Curriculum created."),
            new CurriculumItem($curriculum),
            201
        );
    }

    /**
     * @OA\Get(
     *  path="/curriculums/{curriculum_id}",
     *  tags={"Curriculums"},
     *  summary="Get curriculum data by id",
     *  security={{"bearerAuth":{}}},
     *  @OA\Parameter(
     *      name="curriculum_id", in="path", required=true, @OA\Schema(type="integer"),
     *      description="Curriculum id"
     *  ),
     *  @OA\Response(
     *      response=200, description="Resource updated.",
     *      @OA\JsonContent(allOf={
     *           @OA\Schema(ref="#/components/schemas/SuccessResponse"),
     *           @OA\Schema(@OA\Property(property="message", example="Curriculum data.")),
     *           @OA\Schema(@OA\Property(property="data", ref="#/components/schemas/CurriculumItem"))
     *      })
     *  ),
     *  @OA\Response(response=400, ref="#/components/responses/ErrorResponse400"),
     *  @OA\Response(response=401, ref="#/components/responses/ErrorResponse401"),
     *  @OA\Response(response=403, ref="#/components/responses/ErrorResponse403"),
     *  @OA\Response(response=404, ref="#/components/responses/ErrorResponse404"),
     * )
     *
     * Show the specified resource.
     *
     * @param $curriculum_id
     * @param \Modules\Curriculums\Services\CurriculumService $service
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws AuthorizationException
     */
    public function show($curriculum_id, CurriculumService $service): JsonResponse
    {
        $this->authorize('view-course', [CurriculumsStudentsContractPolicy::class, $curriculum_id]);

        $curriculum = $service->get($curriculum_id);

        return $this->sendSuccessResponse(
            __("Curriculum data."),
            new CurriculumItem($curriculum)
        );
    }

    /**
     * @OA\Post(
     *  path="/curriculums/{curriculum_id}",
     *  tags={"Curriculums"},
     *  summary="Update curriculum.",
     *  security={{"bearerAuth":{}}},
     *  @OA\Parameter(
     *      name="curriculum_id", in="path", required=true, @OA\Schema(type="integer"),
     *      description="Curriculum id"
     *  ),
     *  requestBody={"$ref": "#/components/requestBodies/UpdateCurriculumRequest"},
     *  @OA\Response(
     *      response=200, description="Resource updated.",
     *      @OA\JsonContent(allOf={
     *           @OA\Schema(ref="#/components/schemas/SuccessResponse"),
     *           @OA\Schema(@OA\Property(property="message", example="Curriculum updated.")),
     *           @OA\Schema(@OA\Property(property="data", ref="#/components/schemas/CurriculumItem"))
     *      })
     *  ),
     *  @OA\Response(response=400, ref="#/components/responses/ErrorResponse400"),
     *  @OA\Response(response=401, ref="#/components/responses/ErrorResponse401"),
     *  @OA\Response(response=403, ref="#/components/responses/ErrorResponse403"),
     *  @OA\Response(response=404, ref="#/components/responses/ErrorResponse404"),
     * )
     *
     * Update the specified resource in storage.
     *
     * @param \Modules\Curriculums\Http\Requests\UpdateCurriculumRequest $request
     * @param $curriculum_id
     * @param \Modules\Curriculums\Services\CurriculumService $service
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateCurriculumRequest $request, $curriculum_id, CurriculumService $service): JsonResponse
    {
        $curriculum = $service->update($request->getCurriculumDto());

        return $this->sendSuccessResponse(
            __("Curriculum updated."),
            new CurriculumItem($curriculum)
        );
    }

    /**
     * @OA\Delete(
     *  path="/curriculums/{curriculum_id}",
     *  tags={"Curriculums"},
     *  summary="Delete curriculum.",
     *  security={{"bearerAuth":{}}},
     *  @OA\Parameter(
     *      name="curriculum_id", in="path", required=true, @OA\Schema(type="integer"),
     *      description="Curriculum id"
     *  ),
     *  @OA\Response(
     *      response=200, description="Resource deleted.",
     *      @OA\JsonContent(allOf={
     *           @OA\Schema(ref="#/components/schemas/SuccessResponse"),
     *           @OA\Schema(@OA\Property(property="message", example="Curriculum deleted.")),
     *           @OA\Schema(@OA\Property(property="data", example="null")),
     *      })
     *  ),
     *  @OA\Response(response=400, ref="#/components/responses/ErrorResponse400"),
     *  @OA\Response(response=401, ref="#/components/responses/ErrorResponse401"),
     *  @OA\Response(response=403, ref="#/components/responses/ErrorResponse403"),
     *  @OA\Response(response=404, ref="#/components/responses/ErrorResponse404"),
     * )
     *
     * Remove the specified resource from storage.
     *
     * @param $curriculum_id
     * @param \Modules\Curriculums\Services\CurriculumService $service
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($curriculum_id, CurriculumService $service): JsonResponse
    {
        $service->delete($curriculum_id);

        return $this->sendSuccessResponse(
            "Curriculum deleted."
        );
    }
}
