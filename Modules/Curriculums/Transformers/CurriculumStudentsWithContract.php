<?php

namespace Modules\Curriculums\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class CurriculumStudentsWithContract extends JsonResource
{
    /**
     * @OA\Schema(title="CurriculumStudentsWithContract", schema="CurriculumStudentsWithContract",
     *  @OA\Property(property="curriculum", type="object",
     *     @OA\Property(property="id", type="int", example="CurriculumId"),
     *     @OA\Property(property="name", type="string", example="CurriculumName"),
     *     @OA\Property(
     *        property="students", type="array",
     *        @OA\Items(ref="#/components/schemas/CurriculumItemStudents")
     *     )
     *  ),
     *
     * )
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'curriculum'  => [
                'id'   => $this->id,
                'name' => $this->name,
                'students' => CurriculumItemStudents::collection($this->students),
            ],
        ];
    }
}
