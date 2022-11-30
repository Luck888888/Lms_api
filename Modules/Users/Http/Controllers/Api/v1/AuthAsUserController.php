<?php

namespace Modules\Users\Http\Controllers\Api\v1;

use App\Http\Controllers\Base\BaseApiController;
use Illuminate\Http\JsonResponse;
use Modules\Users\Entities\User;
use Modules\Users\Http\Requests\LoginAsUserRequest;
use Modules\Users\Services\UserService;
use Modules\Users\Transformers\LoginUserItem;

class AuthAsUserController extends BaseApiController
{
    /**
     * @OA\Post(
     *  path="/login_as_user",
     *  tags={"Authentication"},
     *  summary="Login as user.",
     *  security={{"bearerAuth":{}}},
     *  requestBody={"$ref": "#/components/requestBodies/LoginAsUserRequest"},
     *  @OA\Response(
     *      response=200, description="Successfull response.",
     *      @OA\JsonContent(allOf={
     *           @OA\Schema(ref="#/components/schemas/SuccessResponse"),
     *           @OA\Schema(@OA\Property(property="message", example="Profile data.")),
     *           @OA\Schema(@OA\Property(property="data", ref="#/components/schemas/LoginUserItem")),
     *      })
     *  ),
     *  @OA\Response(response=400, ref="#/components/responses/ErrorResponse400"),
     *  @OA\Response(response=401, ref="#/components/responses/ErrorResponse401"),
     *  @OA\Response(response=403, ref="#/components/responses/ErrorResponse403"),
     *  @OA\Response(response=404, ref="#/components/responses/ErrorResponse404"),
     * )
     * Show the specified resource.
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(LoginAsUserRequest $request, UserService $service): JsonResponse
    {
        $this->authorize('loginAsUser', [User::class]);

        $user = $service->get(['user_id' => $request->user_id]);

        $token = $user->createToken("API token")->plainTextToken;

        return $this->sendSuccessResponse(
            __("User data."),
            new LoginUserItem($user, $token)
        );
    }
}
