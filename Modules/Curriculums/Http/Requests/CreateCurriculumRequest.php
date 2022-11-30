<?php

namespace Modules\Curriculums\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Curriculums\Dto\CurriculumDto;

class CreateCurriculumRequest extends FormRequest
{
    /**
     * @OA\RequestBody(
     *     request="CreateCurriculumRequest", required=true,
     *     description="Create course.",
     *     @OA\MediaType(
     *         mediaType="multipart/form-data",
     *         @OA\Schema(
     *              type="object",
     *              @OA\Property(
     *                  property="name", type="string", example="CurriculumName",
     *                  description="The curriculum name."
     *              ),
     *              @OA\Property(
     *                  property="description", type="string", example="CurriculumDescription",
     *                  description="The curriculum description."
     *              ),
     *              @OA\Property(
     *                  property="start_at", type="string", format="date", example="09-07-2022",
     *                  description="The curriculum start date.",
     *              ),
     *              @OA\Property(
     *                  property="end_at", type="string", format="date", example="22-12-2022",
     *                  description="The curriculum start date.",
     *              ),
     *             @OA\Property(
     *                  property="years_of_study", type="integer", enum={"1","2","3"},example="1",
     *                  description="Quantity of years curriculum.",
     *              ),
     *             @OA\Property(
     *                  property="contract_file", type="string", format="binary",
     *                  description="The curriculum contract file.",
     *              ),
     *              required={"name", "description", "start_at", "end_at", "contract_file"}
     *         )
     *     )
     * )
     *
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $date_format = get_date_time_validation_format();

        return [
            "name"           => "required|string",
            "description"    => "required|string",
            'start_at'       => 'required|date|date_format:' . $date_format,
            'end_at'         => 'required|date|date_format:' . $date_format,
            'years_of_study' => 'sometimes|integer|nullable',
            "contract_file"  => "required|file|mimes:pdf",
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
     * @return \Modules\Curriculums\Dto\CurriculumDto
     */
    public function getCurriculumDto(): CurriculumDto
    {
        return new CurriculumDto($this->validated());
    }
}
