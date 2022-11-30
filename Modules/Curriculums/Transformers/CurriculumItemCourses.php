<?php

namespace Modules\Curriculums\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Courses\Transformers\CourseContractStudents;

class CurriculumItemCourses extends JsonResource
{
    /**
     * @OA\Schema(title="CurriculumItemCourses", schema="CurriculumItemCourses",
     *  @OA\Property(property="id", type="integer", example="CourseId"),
     *  @OA\Property(property="name", type="string", example="CourseName"),
     *  @OA\Property(property="year_of_study", type="integer", example="1"),
     *  @OA\Property(property="start_at", type="string", example="CourseStartDate"),
     *  @OA\Property(property="end_at", type="string", example="CourseEndDate"),
     *  @OA\Property(property="signed_contract", type="object", ref="#/components/schemas/CourseContractStudents"),
     * )
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id"            => $this->id,
            'name'          => $this->name,
            'year_of_study' => $this->year_of_study,
            'start_at'      => optional($this->start_at)->format(get_date_time_format()),
            'end_at'        => optional($this->end_at)->format(get_date_time_format()),
            $this->mergeWhen(auth()->user()->hasRole(['student']), [
                'signed_contract' => CourseContractStudents::make($this->contracts->firstWhere('student_id', auth()->id()))
            ])
        ];
    }
}
