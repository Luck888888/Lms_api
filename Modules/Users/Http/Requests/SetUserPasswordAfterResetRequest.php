<?php

namespace Modules\Users\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SetUserPasswordAfterResetRequest extends FormRequest
{
    /**
     *  @OA\RequestBody(
     *     request="SetUserPasswordAfterResetRequest", required=true,
     *     description="Reset password request.",
     *     @OA\MediaType(
     *         mediaType="application/x-www-form-urlencoded",
     *         @OA\Schema(
     *              type="object",
     *              @OA\Property(
     *                  property="email", type="string", format="email", example="user@email.com",
     *                  description="The user email."
     *              ),
     *              @OA\Property(
     *                  property="token", type="string", example="TokenString",
     *                  description="The user reset token."
     *              ),
     *              @OA\Property(
     *                  property="password", type="string", format="password",
     *                  description="The user password."
     *              ),
     *              @OA\Property(
     *                  property="password_confirmation", type="string", format="password",
     *                  description="The user password confirmation."
     *              ),
     *              required={"email", "token", "password", "password_confirmation"}
     *         )
     *     )
     * )
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email'     => 'required|email',
            'token'     => 'required|string',
            'password' => 'required|string|confirmed|case_diff|numbers|letters|symbols|min:6',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
