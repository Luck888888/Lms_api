<?php

namespace Modules\Curriculums\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContractCurriculumRequest extends FormRequest
{
    /**
     * @OA\RequestBody(
     *     request="ContractCurriculumRequest", required=true,
     *     description="Curriculum contract.",
     *     @OA\MediaType(
     *         mediaType="application/x-www-form-urlencoded",
     *         @OA\Schema(
     *              type="object",
     *              @OA\Property(
     *              property="is_sign_contract", type="boolean",
     *              description="The curriculum sign contract.",
     *              ),
     *              @OA\Property(
     *              property="is_sign_offer", type="boolean",
     *              description="The curriculum sign offer.",
     *              ),
     *              required={"is_sign_contract", "is_sign_offer"}
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
        return [
            'is_sign_contract' => 'required|boolean|sign_contract',
            'is_sign_offer'    => 'required|boolean|sign_contract',
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
     * Prepare inputs for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'is_sign_contract' => $this->toBoolean($this->is_sign_contract),
            'is_sign_offer'    => $this->toBoolean($this->is_sign_offer),
        ]);
    }

    /**
     * Convert to boolean
     *
     * @param $booleable
     * @return boolean
     */
    private function toBoolean($booleable)
    {
        return filter_var($booleable, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    }
}
