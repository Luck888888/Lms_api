<?php

namespace Modules\Users\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResetUserPasswordRequest extends FormRequest
{
    /**
     *  @OA\RequestBody(
     *     request="ResetUserPasswordRequest", required=true,
     *     description="Reset password request.",
     *     @OA\MediaType(
     *         mediaType="application/x-www-form-urlencoded",
     *         @OA\Schema(
     *              type="object",
     *              @OA\Property(
     *                  property="email", type="string", format="email", example="user@email.com",
     *                  description="The user email."
     *              ),
     *              required={"email"}
     *         )
     *     )
     * )
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email'
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
