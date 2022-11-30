<?php

namespace Modules\Users\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Users\Dto\ProfileDto;

class UpdateProfileRequest extends FormRequest
{
    /**
     *  @OA\RequestBody(
     *     request="UpdateProfileRequest", required=true,
     *     description="Update profile data.",
     *     @OA\MediaType(
     *         mediaType="application/x-www-form-urlencoded",
     *         @OA\Schema(
     *              type="object",
     *              @OA\Property(
     *                  property="email", type="string", format="email", example="user@email.com",
     *                  description="The user email for login."
     *              ),
     *              @OA\Property(
     *                  property="phone", type="string", example="+14155550132",
     *                  description="The user phone number in international format.",
     *              ),
     *              @OA\Property(
     *                  property="address", type="string", example="User Address",
     *                  description="The user address.",
     *              ),
     *              @OA\Property(
     *                  property="religion", type="string",
     *                  enum={"judaism","christianity","islam","other"},
     *                  description="The user religion.",
     *              ),
     *              @OA\Property(
     *                  property="religion_type", type="string",
     *                  enum={"orthodox","traditional","secular", "other"},
     *                  description="The user religion type (strength =) ).",
     *              ),
     *              @OA\Property(
     *                  property="photo", type="string",
     *                  description="The user profile image.",
     *              ),
     *             @OA\Property(
     *                  property="zip_code", type="string",
     *                  description="The user post index.",
     *              ),
     *              required={}
     *         )
     *     )
     * )
     *
     * @return array
     */
    public function rules()
    {
        $allowed_religion       = get_religions(true);
        $allowed_religion_types = get_religion_types(true);


        return [
            'email'         => 'sometimes|string|email|max:255|unique:users,email,' . auth()->id(),
            'phone'         => 'sometimes|string|min:8',
            'address'       => 'sometimes|string',
            'photo'         => 'sometimes|required|base64image',
            'religion'      => 'sometimes|string|in:' . $allowed_religion,
            'religion_type' => 'sometimes|string|in:' . $allowed_religion_types,
            'zip_code'      => 'sometimes|string',
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

    /**
     * @return \Modules\Users\Dto\ProfileDto
     */
    public function getProfileDto(): ProfileDto
    {
        $data       = $this->validated();
        $data['id'] = (int) auth()->id();
        return new ProfileDto($data);
    }
}
