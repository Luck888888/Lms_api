<?php

namespace Modules\Curriculums\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class CurriculumsCollectionItem extends JsonResource
{
    /**
     * @OA\Schema(title="CurriculumsCollectionItem", schema="CurriculumsCollectionItem",
     *  @OA\Property(property="id", type="int", example="Id"),
     *  @OA\Property(property="name", type="string", example="Name"),
     *  @OA\Property(property="description", type="string", example="Description"),
     *  @OA\Property(property="start_at", type="string", example="2021-10-11"),
     *  @OA\Property(property="end_at", type="string", example="2021-10-11"),
     *  @OA\Property(property="years_of_study", type="integer", example="1"),
     *  @OA\Property(property="created_at", type="string", example="2021-10-11 14:52:25"),
     *  @OA\Property(property="signed_contract", type="object", ref="#/components/schemas/CurriculumContractStudents")
     * )
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id"             => $this->id,
            "name"           => $this->name,
            "description"    => $this->description,
            "start_at"       => optional($this->start_at)->format(get_date_time_format()),
            "end_at"         => optional($this->end_at)->format(get_date_time_format()),
            "years_of_study" => $this->years_of_study,
            "created_at"     => optional($this->created_at)->format(get_date_time_format()),
            $this->mergeWhen(auth()->user()->hasRole(['student']), [
                'signed_contract' => CurriculumContractStudents::make($this->contracts->firstWhere('student_id', auth()->id()))
            ])
        ];
    }
}
