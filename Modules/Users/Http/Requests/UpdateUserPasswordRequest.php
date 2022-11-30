<?php

namespace Modules\Users\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UpdateUserPasswordRequest extends FormRequest
{
    /**
     *  @OA\RequestBody(
     *     request="UpdateUserPasswordRequest", required=true,
     *     description="Update user data.",
     *     @OA\MediaType(
     *         mediaType="application/x-www-form-urlencoded",
     *         encoding={"roles[]":{"explode": true}},
     *         @OA\Schema(
     *              @OA\Property(
     *                  property="current_password", type="string", format="password",
     *                  description="The user current password."
     *              ),
     *              @OA\Property(
     *                  property="password", type="string", format="password",
     *                  description="The user password."
     *              ),
     *              @OA\Property(
     *                  property="password_confirmation", type="string", format="password",
     *                  description="The user password confirmation."
     *              ),
     *              required={"current_password", "password", "password_confirmation"}
     *         )
     *     )
     * )
     *
     * @return array
     */
    public function rules()
    {
        return [
            'current_password' => 'required|current_password:api',
            'password'         => 'required|string|confirmed|case_diff|numbers|letters|symbols|min:6',
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
