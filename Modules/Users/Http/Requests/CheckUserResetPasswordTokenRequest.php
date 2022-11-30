<?php

namespace Modules\Users\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckUserResetPasswordTokenRequest extends FormRequest
{
    /**
     *  @OA\RequestBody(
     *     request="CheckUserResetPasswordTokenRequest", required=true,
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
     *              required={"email", "token"}
     *         )
     *     )
     * )
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'token' => 'required|string'
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
