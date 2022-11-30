<?php

namespace Modules\Curriculums\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Curriculums\Dto\CurriculumFiltersDto;

class GetCurriculumsRequest extends FormRequest
{
    /**
     *
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $allowed_order_by_fields = get_curriculum_allowed_order_by_fields(true);
        $allowed_filter_fields   = get_curriculum_allowed_filter_fields(true);

        return [
            'page'              => 'sometimes',
            'search'            => 'sometimes|string|min:1',
            'order_by'          => 'sometimes|array:' . $allowed_order_by_fields,
            'order_by.*'        => 'required|in:asc,desc',
            'filters'           => 'sometimes|array:' . $allowed_filter_fields,
            'filters.course_id' => 'sometimes',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return \Modules\Curriculums\Dto\CurriculumFiltersDto
     */
    public function getCurriculumsFiltersDto(): CurriculumFiltersDto
    {
        $data         = $this->validated();
        $data['user'] = auth()->user();
        return new CurriculumFiltersDto($data);
    }
}
