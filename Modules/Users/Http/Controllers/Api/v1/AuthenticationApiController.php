<?php

namespace Modules\Users\Http\Controllers\Api\v1;

use App\Http\Controllers\Base\BaseApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Modules\Users\Entities\User;
use Modules\Users\Http\Requests\LoginRequest;
use Modules\Users\Transformers\LoginUserItem;

class AuthenticationApiController extends BaseApiController
{
    /**
     *
     * @OA\Post(
     *  path="/login",
     *  tags={"Authentication"},
     *  summary="User login",
     *  requestBody={"$ref": "#/components/requestBodies/LoginRequest"},
     * @OA\Response(
     *         response=200, description="Successfull response.",
     *         @OA\JsonContent(allOf={
     *              @OA\Schema(ref="#/components/schemas/SuccessResponse"),
     *              @OA\Schema(@OA\Property(property="message", example="Successful authentication.")),
     *              @OA\Schema(@OA\Property(property="data",ref="#/components/schemas/LoginUserItem"))
     *          })
     *     ),
     * @OA\Response(response=400, ref="#/components/responses/ErrorResponse400"),
     * @OA\Response(response=401, ref="#/components/responses/ErrorResponse401"),
     * @OA\Response(response=403, ref="#/components/responses/ErrorResponse403"),
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(LoginRequest $request)
    {
        /** @var User $user */
        $user = User::where('email', $request->email)
                    ->with("roles")
                    ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->sendFailedResponse('Invalid email/password supplied.', [], 401);
        }

        if (!$user->isUserHasAccess()) {
            return $this->sendFailedResponse('Your account is disabled.', [], 401);
        }

        $token = $user->createToken("API token")->plainTextToken;

        return $this->sendSuccessResponse(
            "Successful authentication.",
            new LoginUserItem($user, $token)
        );
    }

    /**
     * @OA\Post(
     *  path="/logout",
     *  tags={"Authentication"},
     *  summary="Log out.",
     *  description="Method revokes the current access token.",
     *  security={{"bearerAuth":{}}},
     *  @OA\Response(response=200, description="Successfull response.",
     *      @OA\JsonContent(allOf={
     *           @OA\Schema(ref="#/components/schemas/SuccessResponse"),
     *           @OA\Schema(@OA\Property(property="message", example="You have been successfully logged out."))
     *       })
     *  ),
     *  @OA\Response(response=401, ref="#/components/responses/ErrorResponse401"),
     * )
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        optional(auth()->user())->currentAccessToken()->delete();
        return $this->sendSuccessResponse("You have been successfully logged out.");
    }
}
