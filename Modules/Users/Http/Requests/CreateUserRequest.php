<?php

namespace Modules\Users\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Users\Dto\UserDto;

class CreateUserRequest extends FormRequest
{
    /**
     *  @OA\RequestBody(
     *     request="CreateUserRequest", required=true,
     *     description="Create user. Password rules - minimum 6 letters - numbers - letters in different case - symbol",
     *     @OA\MediaType(
     *         mediaType="application/x-www-form-urlencoded",
     *         encoding={"roles[]":{"explode": true},"curriculums[]":{"explode": true}},
     *         @OA\Schema(
     *              type="object",
     *              @OA\Property(
     *                  property="email", type="string", format="email", example="user@email.com",
     *                  description="The user email for login."
     *              ),
     *              @OA\Property(
     *                  property="password", type="string", format="password",
     *                  description="The user password."
     *              ),
     *              @OA\Property(
     *                  property="passport", type="string",
     *                  description="The user passport number."
     *              ),
     *              @OA\Property(
     *                  property="full_name", type="string", example="User Full Name",
     *                  description="The user full name.",
     *              ),
     *              @OA\Property(
     *                  property="birth_date", type="string", format="date", example="21-07-2021",
     *                  description="The user birh date.",
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
     *                  property="sex", type="string", enum={"male", "female"},
     *                  description="The user sex.",
     *              ),
     *              @OA\Property(
     *                  property="status", type="string", enum={"enabled", "disabled", "interested"},
     *                  description="The user status.",
     *              ),
     *              @OA\Property(
     *                  property="profession", type="string|int", example="User Profession",
     *                  description="The user profession(id profession or name profession).",
     *              ),
     *              @OA\Property(
     *                     property="is_archived", type="string", enum={"0", "1"},
     *                     description="Set user archive status",
     *              ),
     *              @OA\Property(
     *                  property="roles[]", type="array",
     *                  @OA\Items(type="string", enum={"administrator","teacher","student"}),
     *                  description="The user roles.",
     *              ),
     *              @OA\Property(
     *                  property="religion", type="string",
     *                  enum={"judaism","christianity","islam","other"},
     *                  description="The user religion.",
     *              ),
     *              @OA\Property(
     *                  property="religion_type", type="string",
     *                  enum={"orthodox","traditional","secular","other"},
     *                  description="The user religion type (strength).",
     *              ),
     *              @OA\Property(
     *                  property="photo", type="string",
     *                  description="The user profile image.",
     *              ),
     *              @OA\Property(
     *                  property="zip_code", type="string",
     *                  description="The user post index.",
     *              ),
     *              @OA\Property(
     *                  property="curriculums[]", type="array", @OA\Items(type="string", example="CurriculumId"),
     *                  description="The users curriculums ids (For students only)",
     *              ),
     *              required={ "email", "password", "full_name",
     *                        "birth_date", "phone", "address", "sex", "status",
     *                        "roles[]", "religion", "religion_type"}
     *         )
     *     )
     * )
     *
     * @return array
     */
    public function rules(): array
    {
        $allowed_roles    = get_user_allowed_roles(true);
        $allowed_statuses = get_user_allowed_statuses(true);
        $allowed_sex      = get_user_allowed_sex(true);
        $allowed_religion = get_religions(true);
        $allowed_religion_types = get_religion_types(true);
        $date_format      = get_date_time_validation_format('date_format');

        return [
            'email'         => 'required|string|email|max:255|unique:users,email',
            'password'      => 'required|string|case_diff|numbers|letters|symbols|min:6',
            'passport'      => 'required|string',
            'full_name'     => 'required|string|min:3',
            'birth_date'    => 'required|date|date_format:' . $date_format,
            'phone'         => 'required|string|min:8',
            'address'       => 'required|string',
            'sex'           => 'required|string|in:' . $allowed_sex,
            'status'        => 'required|string|in:' . $allowed_statuses,
            'profession'    => 'nullable|profession_check',
            'is_archived'   => 'sometimes|boolean',
            'roles'         => 'required|array|validation_roles',
            'roles.*'       => 'required|string|in:' . $allowed_roles,
            'photo'         => 'sometimes|base64image',
            'religion'      => 'required|string|in:' . $allowed_religion,
            'religion_type' => 'required|string|in:' . $allowed_religion_types,
            'zip_code'      => 'nullable|string',
            'curriculums'   => 'sometimes|array',
            'curriculums.*' => 'required|string',
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
     * @return \Modules\Users\Dto\UserDto
     */
    public function getUserDto(): UserDto
    {
        return new UserDto($this->validated());
    }
}
