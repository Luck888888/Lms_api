<?php

namespace Modules\Users\Http\Controllers\Api\v1;

use App\Http\Controllers\Base\BaseApiController;
use Illuminate\Http\JsonResponse;
use Modules\Users\Entities\User;
use Modules\Users\Events\UserChangePassword;
use Modules\Users\Events\UserCreateAccount;
use Modules\Users\Http\Requests\CreateUserRequest;
use Modules\Users\Http\Requests\GetUsersRequest;
use Modules\Users\Http\Requests\UpdateUserRequest;
use Modules\Users\Services\UserService;
use Modules\Users\Transformers\UserItem;
use Modules\Users\Transformers\UsersCollection;

class UserApiController extends BaseApiController
{
    /**
     * @OA\Get(
     *  path="/users",
     *  tags={"Users"},
     *  summary="Get users list",
     *  security={{"bearerAuth":{}}},
     *  @OA\Parameter(
     *      name="page", in="query", required=false,
     *      description="Page of users list",
     *      @OA\Schema(type="string", default="1")
     *  ),
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
     *      description="<br/>Allowed filters: <br/>**roles** <br/> _[role_name_1, role_name_2 ...]_ - for users with roles name from array<br/>**statuses** <br/> _[status_1, status_2 ...]_ - for users with statuses from array <br/>**is_archived** - 0|1 <br/>**curriculums** <br/> _[curr_id_1, curr_id_2 ...]_ - for users with curriculums from array<br/>**exclude_users** <br/> _[user_id_1, user_id_2 ...]_ - to exclude users from array<br/>**courses** <br/> _[course_id_1, course_id_2 ...]_ - for users with courses from array<br/>**religion**<br/> religion:string_religion - user's religion<br/>**religion_type**<br/> religion_type:string_religion_type - user's religion_type <br/>**professions** <br/>_[profession_id_1, profession_id_2 ...]_ - for users with professions from array<br/>",
     *      style="deepObject",
     *      @OA\Schema(type="object", example="{'curriculum_id':'0'}"),
     *  ),
     *  @OA\Response(
     *      response=200, description="Successfull response.",
     *      @OA\JsonContent(allOf={
     *           @OA\Schema(ref="#/components/schemas/SuccessResponse"),
     *           @OA\Schema(@OA\Property(property="message", example="Users list.")),
     *           @OA\Schema(@OA\Property(property="data", ref="#/components/schemas/UsersCollection")),
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
     * @param \Modules\Users\Services\UserService $service
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(GetUsersRequest $request, UserService $service): JsonResponse
    {
        $this->authorize('getAll', User::class);

        $service->setResourceQueryClosure(function ($query) {
            return $query->with('curriculums');
        });

        $users = $service->getAll($request->getUserFiltersDto());

        return $this->sendSuccessResponse(
            __("Users list."),
            new UsersCollection($users)
        );
    }

    /**
     * @OA\Post(
     *  path="/users",
     *  tags={"Users"},
     *  summary="Create new user.",
     *  security={{"bearerAuth":{}}},
     *  requestBody={"$ref": "#/components/requestBodies/CreateUserRequest"},
     *  @OA\Response(
     *      response=201, description="Resource created.",
     *      @OA\JsonContent(allOf={
     *           @OA\Schema(ref="#/components/schemas/SuccessResponse"),
     *           @OA\Schema(@OA\Property(property="message", example="User created.")),
     *           @OA\Schema(@OA\Property(property="data", ref="#/components/schemas/UserItem"))
     *      })
     *  ),
     *  @OA\Response(response=400, ref="#/components/responses/ErrorResponse400"),
     *  @OA\Response(response=401, ref="#/components/responses/ErrorResponse401"),
     *  @OA\Response(response=403, ref="#/components/responses/ErrorResponse403"),
     * )
     *
     * Store a newly created resource in storage.
     *
     * @param \Modules\Users\Http\Requests\CreateUserRequest $request
     * @param \Modules\Users\Services\UserService $service
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \ErrorException
     */
    public function store(CreateUserRequest $request, UserService $service): JsonResponse
    {
        $this->authorize('create', User::class);
        $user = $service->create($request->getUserDto());

        if ($user) {
            UserCreateAccount::dispatch($user, $request->getUserDto()->getPassword());
        }

        return $this->sendSuccessResponse(
            __("User created."),
            new UserItem($user),
            201
        );
    }

    /**
     * @OA\Get(
     *  path="/users/{user_id}",
     *  tags={"Users"},
     *  summary="Get user data by id",
     *  security={{"bearerAuth":{}}},
     *  @OA\Parameter(
     *      name="user_id", in="path", required=true, @OA\Schema(type="integer"),
     *      description="User id"
     *  ),
     *  @OA\Response(
     *      response=200, description="Successfull response.",
     *      @OA\JsonContent(allOf={
     *           @OA\Schema(ref="#/components/schemas/SuccessResponse"),
     *           @OA\Schema(@OA\Property(property="message", example="User created.")),
     *           @OA\Schema(@OA\Property(property="data", ref="#/components/schemas/UserItem")),
     *      })
     *  ),
     *  @OA\Response(response=400, ref="#/components/responses/ErrorResponse400"),
     *  @OA\Response(response=401, ref="#/components/responses/ErrorResponse401"),
     *  @OA\Response(response=403, ref="#/components/responses/ErrorResponse403"),
     *  @OA\Response(response=404, ref="#/components/responses/ErrorResponse404"),
     * )
     *
     * @param $user_id
     * @param \Modules\Users\Services\UserService $service
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($user_id, UserService $service): JsonResponse
    {
        $this->authorize('view', [
            User::class,
            $user_id
        ]);
        $service->setResourceQueryClosure(function ($query) {
            return $query->with(["roles", "curriculums"]);
        });

        $user = $service->get($user_id);

        return $this->sendSuccessResponse(
            __("User data."),
            new UserItem($user)
        );
    }

    /**
     * @OA\Patch (
     *  path="/users/{user_id}",
     *  tags={"Users"},
     *  summary="Update existing user.",
     *  security={{"bearerAuth":{}}},
     *  @OA\Parameter(
     *      name="user_id", in="path", required=true, @OA\Schema(type="string"),
     *      description="User id"
     *  ),
     *  requestBody={"$ref": "#/components/requestBodies/UpdateUserRequest"},
     *  @OA\Response(
     *      response=200, description="Resource updated.",
     *      @OA\JsonContent(allOf={
     *           @OA\Schema(ref="#/components/schemas/SuccessResponse"),
     *           @OA\Schema(@OA\Property(property="message", example="User updated.")),
     *           @OA\Schema(@OA\Property(property="data", ref="#/components/schemas/UserItem"))
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
     * @param \Modules\Users\Http\Requests\UpdateUserRequest $request
     * @param $user_id
     * @param \Modules\Users\Services\UserService $service
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateUserRequest $request, $user_id, UserService $service): JsonResponse
    {
        $this->authorize('update', [
            User::class,
            $user_id
        ]);
        $user = $service->update($request->getUserDto());

        if ($user->wasChanged('password') && $request->getUserDto()->getIsSendPasswordToUser() == 1) {
            UserChangePassword::dispatch($user, $request->getUserDto()->getPassword());
        }

        return $this->sendSuccessResponse(
            __("User updated."),
            new UserItem($user)
        );
    }

    /**
     * @OA\Delete(
     *  path="/users/{user_id}",
     *  tags={"Users"},
     *  summary="Delete user.",
     *  security={{"bearerAuth":{}}},
     *  @OA\Parameter(
     *      name="user_id", in="path", required=true, @OA\Schema(type="string"),
     *      description="User id"
     *  ),
     *  @OA\Response(
     *      response=200, description="Resource deleted.",
     *      @OA\JsonContent(allOf={
     *           @OA\Schema(ref="#/components/schemas/SuccessResponse"),
     *           @OA\Schema(@OA\Property(property="message", example="User deleted.")),
     *           @OA\Schema(@OA\Property(property="data", example="null")),
     *      })
     *  ),
     *  @OA\Response(response=401, ref="#/components/responses/ErrorResponse401"),
     *  @OA\Response(response=403, ref="#/components/responses/ErrorResponse403"),
     *  @OA\Response(response=404, ref="#/components/responses/ErrorResponse404"),
     *  @OA\Response(
     *      response=409, description="Conflict.",
     *      @OA\JsonContent(allOf={
     *           @OA\Schema(ref="#/components/schemas/ErrorResponse"),
     *           @OA\Schema(@OA\Property(property="message", example="User can't be deleted.")),
     *      })
     *  ),
     * )
     * Remove the specified resource from storage.
     *
     * @param $user_id
     * @param \Modules\Users\Services\UserService $service
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function destroy($user_id, UserService $service): JsonResponse
    {
        $this->authorize('delete', [
            User::class,
            $user_id
        ]);

        $service->delete($user_id);

        return $this->sendSuccessResponse(
            __("User deleted.")
        );
    }
}
