<?php

namespace Modules\Users\Http\Controllers\Api\v1;

use App\Http\Controllers\Base\BaseApiController;
use Modules\Users\Http\Requests\CheckUserResetPasswordTokenRequest;
use Modules\Users\Http\Requests\ResetUserPasswordRequest;
use Modules\Users\Http\Requests\SetUserPasswordAfterResetRequest;
use Modules\Users\Services\ResetPasswordService;

class ResetPasswordApiController extends BaseApiController
{
    /**
     * @OA\Post(
     *  path="/password/reset",
     *  tags={"Password - reset"},
     *  summary="Password reset request",
     *  requestBody={"$ref":"#/components/requestBodies/ResetUserPasswordRequest"},
     * @OA\Response(
     *         response=200, description="Successfull response.",
     *         @OA\JsonContent(allOf={
     *              @OA\Schema(ref="#/components/schemas/SuccessResponse"),
     *              @OA\Schema(@OA\Property(property="message", example="Password reset notification was sent.")),
     *              @OA\Schema(@OA\Property(property="data"))
     *          })
     *     ),
     * @OA\Response(response=400, ref="#/components/responses/ErrorResponse400"),
     * @OA\Response(response=401, ref="#/components/responses/ErrorResponse401"),
     * @OA\Response(response=403, ref="#/components/responses/ErrorResponse403"),
     * )
     *
     * Display a listing of the resource.
     *
     * @param ResetUserPasswordRequest $request
     * @param \Modules\Users\Services\ResetPasswordService $service
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * Store a newly created resource in storage.
     * @throws \Exception
     */
    public function store(ResetUserPasswordRequest $request, ResetPasswordService $service)
    {
        $reset_result = $service->send($request->email);

        return $this->sendSuccessResponse(
            "Reset password result.",
            [
                "is_successful" => $reset_result
            ]
        );
    }

    /**
     * @OA\Post(
     *  path="/password/reset/check",
     *  tags={"Password - reset"},
     *  summary="Check reset password token request",
     *  requestBody={"$ref":"#/components/requestBodies/CheckUserResetPasswordTokenRequest"},
     * @OA\Response(
     *         response=200, description="Successfull response.",
     *         @OA\JsonContent(allOf={
     *              @OA\Schema(ref="#/components/schemas/SuccessResponse"),
     *              @OA\Schema(@OA\Property(property="message", example="Token validation result.")),
     *              @OA\Schema(@OA\Property(property="data",
     *                  @OA\Property(property="is_token_valid", example="0|1")
     *              ))
     *          })
     *     ),
     * @OA\Response(response=400, ref="#/components/responses/ErrorResponse400"),
     * @OA\Response(response=401, ref="#/components/responses/ErrorResponse401"),
     * @OA\Response(response=403, ref="#/components/responses/ErrorResponse403"),
     * )
     *
     * Show the specified resource.
     *
     * @param \Modules\Users\Http\Requests\CheckUserResetPasswordTokenRequest $request
     * @param \Modules\Users\Services\ResetPasswordService $service
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function show(CheckUserResetPasswordTokenRequest $request, ResetPasswordService $service)
    {
        $is_checked = $service->check($request->email, $request->token);

        return $this->sendSuccessResponse(
            "Token validation result.",
            [
                "is_token_valid" => $is_checked
            ]
        );
    }

    /**
     * @OA\Post(
     *  path="/password/reset/set",
     *  tags={"Password - reset"},
     *  summary="Set new password after reset",
     *  requestBody={"$ref": "#/components/requestBodies/SetUserPasswordAfterResetRequest"},
     * @OA\Response(
     *         response=200, description="Successfull response.",
     *         @OA\JsonContent(allOf={
     *              @OA\Schema(ref="#/components/schemas/SuccessResponse"),
     *              @OA\Schema(@OA\Property(property="message", example="Password reset result.")),
     *              @OA\Schema(@OA\Property(property="data",
     *                  @OA\Property(property="is_password_changed", example="0|1")
     *              ))
     *          })
     *     ),
     * @OA\Response(response=400, ref="#/components/responses/ErrorResponse400"),
     * @OA\Response(response=401, ref="#/components/responses/ErrorResponse401"),
     * @OA\Response(response=403, ref="#/components/responses/ErrorResponse403"),
     * )
     *
     * Update the specified resource in storage.
     *
     * @param \Modules\Users\Http\Requests\SetUserPasswordAfterResetRequest $request
     * @param \Modules\Users\Services\ResetPasswordService $service
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(SetUserPasswordAfterResetRequest $request, ResetPasswordService $service)
    {
        $is_password_changed = $service->setNewPassword(
            $request->email,
            $request->token,
            $request->password,
        );

        return $this->sendSuccessResponse(
            "Password reset result.",
            [
                "is_password_changed" => $is_password_changed
            ]
        );
    }
}
