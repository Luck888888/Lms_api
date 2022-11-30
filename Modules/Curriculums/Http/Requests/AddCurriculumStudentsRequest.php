<?php

namespace Modules\Curriculums\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddCurriculumStudentsRequest extends FormRequest
{
    /**
     * @OA\RequestBody(
     *     request="AddCurriculumStudentsRequest", required=true,
     *     description="Add curriculum students.",
     *     @OA\MediaType(
     *         mediaType="application/x-www-form-urlencoded",
     *         encoding={"students[]":{"explode": true}},
     *         @OA\Schema(
     *              @OA\Property(
     *                  property="students[]", type="array", @OA\Items(type="integer", example="StudentId"),
     *                  description="Array of students ids",
     *              ),
     *              required={"students[]"}
     *         )
     *     ),
     *     @OA\MediaType(
     *         mediaType="application/json",
     *         @OA\Schema(
     *              @OA\Property(
     *                  property="students", type="array", @OA\Items(type="integer", example="StudentId"),
     *                  description="Array of students ids",
     *              ),
     *              required={"students"}
     *         )
     *     ),
     * )
     *
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'students'   => "required|array",
            'students.*' => "required|integer|exists:users,id",
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
