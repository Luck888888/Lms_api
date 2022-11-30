<?php

namespace Modules\Users\Http\Controllers\Api\v1;

use App\Http\Controllers\Base\BaseApiController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Users\Entities\User;
use Modules\Users\Http\Requests\GetUsersExportRequest;
use Modules\Users\Http\Requests\GetUsersRequest;
use Modules\Users\Services\UsersExportService;

class UsersExportController extends BaseApiController
{
    /**
     * @OA\Get(
     *  path="/users/export",
     *  tags={"Users"},
     *  summary="Export users list",
     *  security={{"bearerAuth":{}}},
     *  @OA\Parameter(
     *      name="search", in="query", required=false,
     *      description="Search users by search phrase. Requirements: min 3 symbols",
     *      @OA\Schema(type="string")
     *  ),
     *  @OA\Parameter(
     *      name="order_by", in="query", required=false,
     *      description="_Allowed fields params:_ **full_name, email, created_at**. _Direction:_ **desc, asc**",
     *      style="deepObject",
     *      @OA\Schema(type="object", example="{'created_at':'asc'}"),
     *  ),
     *  @OA\Parameter(
     *      name="filters", in="query", required=false,
     *      description="<br/>Allowed filters: <br/>**roles** <br/> _[role_name_1, role_name_2 ...]_ - for users with roles name from array<br/>**statuses** <br/> _[status_1, status_2 ...]_ - for users with statuses from array <br/>**is_archived** - 0|1 <br/>**curriculums** <br/> _[curr_id_1, curr_id_2 ...]_ - for users with curriculums from array<br/>**exclude_users** <br/> _[user_id_1, user_id_2 ...]_ - to exclude users from array<br/>**courses** <br/> _[course_id_1, course_id_2 ...]_ - for users with courses from array<br/>**religion**<br/> religion:string_religion - user's religion<br/>**professions** <br/>_[profession_id_1, profession_id_2 ...]_ - for users with professions from array<br/>",
     *      style="deepObject",
     *      @OA\Schema(type="object", example="{'curriculum_id':'0'}"),
     *  ),
     * @OA\Parameter(
     *      name="file_format", in="query", required=false,
     *      description="<br/> Allowed formats: _csv, xls, xlsx, ods_ - file format for export<br/>",
     *      @OA\Schema(type="string", default="csv", enum={"csv","xls", "xlsx", "ods"}),
     *  ),
     *  @OA\Response(
     *      response=200, description="Successfull response.",
     *      @OA\JsonContent(allOf={
     *           @OA\Schema(ref="#/components/schemas/SuccessResponse"),
     *           @OA\Schema(@OA\Property(property="message", example="Users were successfully exported.")),
     *           @OA\Schema(@OA\Property(property="data",@OA\Property(property="url", example="Export file url"),)),
     *      })
     *  ),
     *  @OA\Response(response=400, ref="#/components/responses/ErrorResponse400"),
     *  @OA\Response(response=401, ref="#/components/responses/ErrorResponse401"),
     *  @OA\Response(response=403, ref="#/components/responses/ErrorResponse403"),
     * )
     *
     * Display a listing of the resource.
     *
     * @param \Modules\Users\Http\Requests\GetUsersRequest $request
     *
     * @return array|false|int|string
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(GetUsersExportRequest $request, UsersExportService $service)
    {
        $this->authorize('getAll', User::class);

        $file_url = $service->generateExportFile($request->getUserExportDto());
        $result = download_file_by_url($file_url);

        if (is_null($result)) {
            throw new ModelNotFoundException("File not found or not created", 404);
        }

        return $result;
    }
}
