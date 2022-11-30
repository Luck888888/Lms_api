<?php

namespace Modules\Curriculums\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class CurriculumItem extends JsonResource
{
    /**
     * @OA\Schema(title="CurriculumItem", schema="CurriculumItem",
     *  @OA\Property(property="id", type="int", example="CurriculumId"),
     *  @OA\Property(property="name", type="string", example="CurriculumName"),
     *  @OA\Property(property="description", type="string", example="CurriculumDescription"),
     *  @OA\Property(
     *      property="students", type="array",
     *      @OA\Items(ref="#/components/schemas/CurriculumItemStudents")
     *  ),
     *  @OA\Property(
     *      property="courses", type="array",
     *      @OA\Items(ref="#/components/schemas/CurriculumItemCourses")
     *  ),
     *  @OA\Property(property="start_at", type="string", example="2021-10-11"),
     *  @OA\Property(property="end_at", type="string", example="2021-10-11"),
     *  @OA\Property(property="years_of_study", type="integer", example="1"),
     *  @OA\Property(property="contract_file_url", type="string", example="ContractFileUrl"),
     * )
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            "id"                => $this->id,
            "name"              => $this->name,
            "description"       => $this->description,
            "students"          => CurriculumItemStudents::collection($this->students),
            "courses"           => CurriculumItemCourses::collection($this->courses),
            "start_at"          => optional($this->start_at)->format(get_date_time_format()),
            "end_at"            => optional($this->end_at)->format(get_date_time_format()),
            "years_of_study"    => $this->years_of_study,
            "contract_file_url" => optional($this->contractFile)->url,
        ];

        return $data;
    }
}
