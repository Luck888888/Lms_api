<?php

namespace Modules\Curriculums\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class CurriculumStudents extends JsonResource
{
    /**
     * @OA\Schema(title="CurriculumStudents", schema="CurriculumStudents",
     *  @OA\Property(property="id", type="int", example="StudentId"),
     *  @OA\Property(property="full_name", type="string", example="StudentFullName"),
     *  @OA\Property(property="email", type="string", example="StudentEmail"),
     *  @OA\Property(property="phone", type="string", example="StudentPhone"),
     *  @OA\Property(property="photo_url", type="string", example="StudentPhotoUrl"),
     *  ),
     * )
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id"        => $this->id,
            "full_name" => $this->full_name,
            "email"     => $this->email,
            'photo_url' => optional($this->photo)->url,
        ];
    }
}
