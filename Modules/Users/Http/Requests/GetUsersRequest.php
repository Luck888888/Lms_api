<?php

namespace Modules\Users\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Users\Dto\UserFiltersDto;

class GetUsersRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $allowed_roles           = get_user_allowed_roles(true);
        $allowed_order_by_fields = get_user_allowed_order_by_fields(true);
        $allowed_user_statuses   = get_user_allowed_statuses(true);
        $allowed_filter_fields   = get_user_allowed_filter_fields(true);


        return [
            'page'                    => 'sometimes',
            'search'                  => 'sometimes|string|min:1',
            'order_by'                => 'sometimes|array:' . $allowed_order_by_fields,
            'order_by.*'              => 'required|in:asc,desc',
            'filters'                 => 'sometimes|array:' . $allowed_filter_fields,
            'filters.*'               => 'required',
            'filters.is_archived'     => 'sometimes|boolean',
            'filters.roles'           => 'sometimes|array',
            'filters.roles.*'         => 'required|in:' . $allowed_roles,
            'filters.statuses'        => 'sometimes|array',
            'filters.statuses.*'      => 'required|in:' . $allowed_user_statuses,
            'filters.curriculums'     => 'sometimes|array',
            'filters.curriculums.*'   => 'required',
            'filters.exclude_users'   => 'sometimes|array',
            'filters.exclude_users.*' => 'required',
            'filters.courses'       => 'sometimes|array',
            'filters.courses.*'     => 'required',
            'filters.religion'      => 'sometimes|string',
            'filters.religion_type' => 'sometimes|string',
            'filters.professions'   => 'sometimes|array',
            'filters.professions.*' => 'required',
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
     * @return \Modules\Users\Dto\UserFiltersDto
     */
    public function getUserFiltersDto(): UserFiltersDto
    {
        $data         = $this->validated();
        $data['user'] = auth()->user();
        return new UserFiltersDto($data);
    }
}
