<?php

namespace Modules\Curriculums\Http\Controllers\Api\v1;

use App\Http\Controllers\Base\BaseApiController;
use Illuminate\Http\JsonResponse;
use Modules\Curriculums\Entities\Curriculum;
use Modules\Curriculums\Events\CurriculumSignContract;
use Modules\Curriculums\Http\Requests\ContractCurriculumRequest;
use Modules\Curriculums\Policies\CurriculumsStudentsContractPolicy;
use Modules\Curriculums\Services\CurriculumService;
use Modules\Curriculums\Services\CurriculumStudentsContractsService;
use Modules\Curriculums\Transformers\CurriculumContract;

class CurriculumStudentsContractsApiController extends BaseApiController
{
    /**
     * @OA\Get(
     *  path="/curriculums/{curriculum_id}/contract",
     *  tags={"Curriculums - contracts"},
     *  summary="Get curriculum contract.",
     *  security={{"bearerAuth":{}}},
     *  @OA\Parameter(
     *      name="curriculum_id", in="path", required=true,
     *     @OA\Schema(type="integer"), description="Curriculum id"
     *  ),
     *  @OA\Response(
     *      response=200, description="Successfull response.",
     *      @OA\JsonContent(allOf={
     *          @OA\Schema(ref="#/components/schemas/SuccessResponse"),
     *          @OA\Schema(@OA\Property(property="message", example="Curriculum contract.")),
     *          @OA\Schema(@OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/CurriculumContract"))),
     *      })
     *  ),
     *  @OA\Response(response=400, ref="#/components/responses/ErrorResponse400"),
     *  @OA\Response(response=401, ref="#/components/responses/ErrorResponse401"),
     *  @OA\Response(response=403, ref="#/components/responses/ErrorResponse403"),
     * )
     *
     * Display a listing of the resource.
     *
     * @param \Modules\Curriculums\Services\CurriculumStudentsContractsService $contract_service
     * @param $curriculum_id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(CurriculumService $service, $curriculum_id): JsonResponse
    {
        $this->authorize('view', [CurriculumsStudentsContractPolicy::class, $curriculum_id]);

        $curriculum = $service->get($curriculum_id);


        return $this->sendSuccessResponse(
            __("Curriculum contract."),
            new CurriculumContract($curriculum)
        );
    }
    /**
     * @OA\Post(
     *  path="/curriculums/{curriculum_id}/contracts",
     *  tags={"Curriculums - contracts"},
     *  summary="Save curriculum contract.For students only",
     *  security={{"bearerAuth":{}}},
     *  @OA\Parameter(
     *      name="curriculum_id", in="path", required=true,
     *     @OA\Schema(type="integer"), description="Curriculum id"
     *  ),
     *  requestBody={"$ref": "#/components/requestBodies/ContractCurriculumRequest"},
     *  @OA\Response(
     *      response=201, description="Successfull response.",
     *      @OA\JsonContent(allOf={
     *          @OA\Schema(ref="#/components/schemas/SuccessResponse"),
     *          @OA\Schema(@OA\Property(property="message", example="StudentContract saved.")),
     *          @OA\Schema(@OA\Property(property="data", example="null")),
     *      })
     *  ),
     *  @OA\Response(response=400, ref="#/components/responses/ErrorResponse400"),
     *  @OA\Response(response=401, ref="#/components/responses/ErrorResponse401"),
     *  @OA\Response(response=403, ref="#/components/responses/ErrorResponse403"),
     * )
     * Store a newly created resource in storage.
     *
     * @param \Modules\Curriculums\Http\Requests\ContractCurriculumRequest $request
     * @param \Modules\Curriculums\Services\CurriculumStudentsContractsService $service
     * @param $curriculum_id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(ContractCurriculumRequest $request, CurriculumStudentsContractsService $service, $curriculum_id): JsonResponse
    {
        $this->authorize('create-contract', [CurriculumsStudentsContractPolicy::class, $curriculum_id]);
        $curriculum_contract = $service->findOrCreate($curriculum_id, auth()->id());

        $curriculum = Curriculum::find($curriculum_id);

        if ($curriculum_contract) {
            CurriculumSignContract::dispatch($curriculum, auth()->id());
        }

        return $this->sendSuccessResponse(
            __("Student сontract saved.")
        );
    }
}
