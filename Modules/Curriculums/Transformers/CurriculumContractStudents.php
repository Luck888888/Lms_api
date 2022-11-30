<?php

namespace Modules\Curriculums\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class CurriculumContractStudents extends JsonResource
{
    /**
     * @OA\Schema(title="CurriculumContractStudents", schema="CurriculumContractStudents",
     *  @OA\Property(property="created_at", type="string", example="2023-10-11 14:52:25"),
     * )
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'created_at'   => optional($this->created_at)->format(get_date_time_format()),
        ];
    }
}
