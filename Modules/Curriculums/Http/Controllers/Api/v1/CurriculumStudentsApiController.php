<?php

namespace Modules\Curriculums\Http\Controllers\Api\v1;

use App\Http\Controllers\Base\BaseApiController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Curriculums\Entities\Curriculum;
use Modules\Curriculums\Events\CurriculumAddStudent;
use Modules\Curriculums\Http\Requests\AddCurriculumStudentsRequest;
use Modules\Curriculums\Http\Requests\DeleteCurriculumStudentsRequest;
use Modules\Curriculums\Policies\CurriculumsStudentPolicy;
use Modules\Curriculums\Services\CurriculumStudentsService;
use Modules\Curriculums\Transformers\CurriculumItemStudents;
use Modules\Curriculums\Transformers\CurriculumStudentsWithContract;
use Modules\Curriculums\Transformers\CurriculumStudents;

class CurriculumStudentsApiController extends BaseApiController
{
    /**
     * @OA\Get(
     *  path="/curriculums/{curriculum_id}/students",
     *  tags={"Curriculum students"},
     *  summary="Get curriculum students",
     *  security={{"bearerAuth":{}}},
     *  @OA\Parameter(
     *      name="curriculum_id", in="path", required=true, @OA\Schema(type="integer"),
     *      description="Curriculum id"
     *  ),
     *  @OA\Parameter(
     *      name="filters", in="query", required=false,
     *      description="<br/>Allowed filters: <br/>**exclude_courses**  _[course_id1, course_id2 ...]_ - for exclude users in courses with id from array",
     *      style="deepObject",
     *      @OA\Schema(type="object", example="{'curriculum_id':'0'}"),
     *  ),
     *  @OA\Response(
     *      response=200, description="Resource updated.",
     *      @OA\JsonContent(allOf={
     *           @OA\Schema(ref="#/components/schemas/SuccessResponse"),
     *           @OA\Schema(@OA\Property(property="message", example="Curriculum students data.")),
     *           @OA\Schema(@OA\Property(property="data", ref="#/components/schemas/CurriculumStudentsWithContract"))
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
     * @param \Illuminate\Http\Request $request
     * @param $curriculum_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, $curriculum_id): JsonResponse
    {
        /** TODO вынести в сервисный класс */
        $curriculum = Curriculum::select(['curriculums.id', 'name'])
                                ->where('curriculums.id', $curriculum_id)
                                ->with([
                                    'students' => function ($query) use ($request, $curriculum_id) {
                                        $query->select(['users.id','email','full_name','photo_id','phone']);
                                        $query->with(['contracts'  => function ($contract_query) use ($curriculum_id) {
                                            $contract_query->where('contractable_id', $curriculum_id);
                                            $contract_query->where('contractable_type', Curriculum::class);
                                        }]);
                                        if (
                                            $request->filters && isset($request->filters['exclude_courses'])
                                            && is_array($request->filters['exclude_courses'])
                                        ) {
                                            $exclude_ids = DB::table('course_student')
                                                             ->whereIn('course_id', $request->filters['exclude_courses'])
                                                             ->get()
                                                             ->pluck('user_id')
                                                             ->toArray();
                                            if ($exclude_ids) {
                                                $query->whereNotIn('users.id', $exclude_ids);
                                            }
                                        }
                                    }
                                ])
                                ->first();

        if (!$curriculum) {
            throw new ModelNotFoundException("Curriculum not found.", 404);
        }

        /** TODO сделать чтоб метод возвращал только список студентов */
        return $this->sendSuccessResponse(
            __("Curriculum students data."),
            new CurriculumStudentsWithContract($curriculum)
        );
    }

    /**
     * @OA\Post(
     *  path="/curriculums/{curriculum_id}/students",
     *  tags={"Curriculum students"},
     *  summary="Add users to curriculum students.",
     *  security={{"bearerAuth":{}}},
     *  @OA\Parameter(
     *      name="curriculum_id", in="path", required=true,
     *     @OA\Schema(type="integer"), description="Curriculum id"
     *  ),
     *  requestBody={"$ref":
     *     "#/components/requestBodies/AddCurriculumStudentsRequest"},
     *  @OA\Response(
     *      response=200, description="Resource updated.",
     *      @OA\JsonContent(allOf={
     *           @OA\Schema(ref="#/components/schemas/SuccessResponse"),
     *           @OA\Schema(@OA\Property(property="message", example="Curriculum students added.")),
     *           @OA\Schema(@OA\Property(property="data", type="array",
     *     @OA\Items(ref="#/components/schemas/CurriculumStudents"))),
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
     * @param \Modules\Curriculums\Http\Requests\AddCurriculumStudentsRequest $request
     * @param \Modules\Curriculums\Services\CurriculumStudentsService $service
     * @param $curriculum_id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(AddCurriculumStudentsRequest $request, CurriculumStudentsService $service, $curriculum_id): JsonResponse
    {
        $this->authorize('create', [
            CurriculumsStudentPolicy::class,
            $curriculum_id
        ]);
        $students = $service->add($curriculum_id, $request->students);

         $curriculum = Curriculum::find($curriculum_id);

        if ($students) {
            CurriculumAddStudent::dispatch($curriculum, $request->students);
        }

        return $this->sendSuccessResponse(
            __("Curriculum students added."),
            CurriculumStudents::collection($students)
        );
    }

    /**
     * @OA\Delete(
     *  path="/curriculums/{curriculum_id}/students",
     *  tags={"Curriculum students"},
     *  summary="Delete users from curriculum students.",
     *  security={{"bearerAuth":{}}},
     *  @OA\Parameter(
     *      name="curriculum_id", in="path", required=true,
     *     @OA\Schema(type="integer"), description="Curriculum id"
     *  ),
     *  requestBody=
     *  {"$ref":"#/components/requestBodies/DeleteCurriculumStudentsRequest"},
     *  @OA\Response(
     *      response=200, description="Resource updated.",
     *      @OA\JsonContent(allOf={
     *           @OA\Schema(ref="#/components/schemas/SuccessResponse"),
     *           @OA\Schema(@OA\Property(property="message", example="Course students deleted.")),
     *           @OA\Schema(@OA\Property(property="data", type="array",
     *     @OA\Items(ref="#/components/schemas/CurriculumItemStudents"))),
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
     * @param \Modules\Curriculums\Http\Requests\DeleteCurriculumStudentsRequest $request
     * @param \Modules\Curriculums\Services\CurriculumStudentsService $service
     * @param $curriculum_id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(DeleteCurriculumStudentsRequest $request, CurriculumStudentsService $service, $curriculum_id): JsonResponse
    {
        $this->authorize('delete', [
            CurriculumsStudentPolicy::class,
            $curriculum_id
        ]);
        $students = $service->delete($curriculum_id, $request->students);

        return $this->sendSuccessResponse(
            __("Curriculum students deleted."),
            CurriculumItemStudents::collection($students)
        );
    }
}
