<?php

namespace Modules\Users\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Users\Dto\UserDto;

class UpdateUserRequest extends FormRequest
{
    /**
     *  @OA\RequestBody(
     *     request="UpdateUserRequest", required=true,
     *     description="Update user data.",
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
     *                   property="is_send_password_to_user", type="string", enum={"0", "1"},
     *                   description="Notify the user about the password change?",
     *              ),
     *              @OA\Property(
     *                  property="passport", type="string", example="PassportNumber",
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
     *                  enum={"orthodox","traditional","secular", "other"},
     *                  description="The user religion type (strength =) ).",
     *              ),
     *              @OA\Property(
     *                  property="curriculums[]", type="array", @OA\Items(type="string", example="CurriculumId"),
     *                  description="The users curriculums ids (For students only)",
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
     *     ),
     *     @OA\MediaType(
     *         mediaType="application/json",
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
     *                   property="is_send_password_to_user", type="string", enum={"0", "1"},
     *                   description="Notify the user about the password change?",
     *              ),
     *              @OA\Property(
     *                  property="passport", type="string", example="PassportNumber",
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
     *                  property="roles", type="array",
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
     *                  enum={"orthodox","traditional","secular"},
     *                  description="The user religion type (strength =) ).",
     *              ),
     *              @OA\Property(
     *                  property="curriculums", type="array", @OA\Items(type="string", example="CurriculumId"),
     *                  description="The users curriculums ids (For students only)",
     *              ),
     *              @OA\Property(
     *                  property="photo", type="string",
     *                  description="The user profile image.",
     *              ),
     *              @OA\Property(
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
        $allowed_roles = get_user_allowed_roles(true);
        $allowed_statuses = get_user_allowed_statuses(true);
        $allowed_sex = get_user_allowed_sex(true);
        $allowed_religion = get_religions(true);
        $allowed_religion_types = get_religion_types(true);
        $date_format = get_date_time_validation_format('date_format');

        return [
            'email'                    => 'sometimes|string|email|max:255|unique:users,email,' . $this->user_id,
            'password'                 => 'sometimes|string|case_diff|numbers|letters|symbols|min:6',
            'passport'                 => 'sometimes|string',
            'full_name'                => 'sometimes|string|min:3',
            'birth_date'               => 'sometimes|date|date_format:' . $date_format,
            'phone'                    => 'sometimes|string|min:8',
            'address'                  => 'sometimes|string',
            'sex'                      => 'sometimes|string|in:' . $allowed_sex,
            'status'                   => 'sometimes|string|in:' . $allowed_statuses,
            'profession'               => 'nullable|profession_check',
            'is_archived'              => 'sometimes|boolean',
            'roles'                    => 'sometimes|array|validation_roles',
            'roles.*'                  => 'sometimes|string|in:' . $allowed_roles,
            'photo'                    => 'sometimes|required|base64image',
            'religion'                 => 'sometimes|string|in:' . $allowed_religion,
            'religion_type'            => 'sometimes|string|in:' . $allowed_religion_types,
            'zip_code'                 => 'sometimes|string',
            'curriculums'              => 'sometimes|array',
            'curriculums.*'            => 'required|string',
            'is_send_password_to_user' => 'sometimes|boolean',
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
        $data = $this->validated();
        $data['id'] = (int)$this->route('user_id');
        return new UserDto($data);
    }
}
