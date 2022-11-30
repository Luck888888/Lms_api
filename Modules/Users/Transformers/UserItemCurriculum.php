<?php

namespace Modules\Users\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class UserItemCurriculum extends JsonResource
{
    /**
     * @OA\Schema(title="UserItemCurriculum", schema="UserItemCurriculum",
     *  @OA\Property(property="id", type="int", example="CurriculumId"),
     *  @OA\Property(property="name", type="string", example="CurriculumName"),
     * )
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'   => $this->id,
            'name' => $this->name,
            'start_at' => optional($this->start_at)->format(get_date_time_format()),
            'end_at'   => optional($this->end_at)->format(get_date_time_format())
        ];
    }
}
