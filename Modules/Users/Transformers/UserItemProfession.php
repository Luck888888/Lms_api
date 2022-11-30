<?php

namespace Modules\Users\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class UserItemProfession extends JsonResource
{
    /**
     * @OA\Schema(title="UserItemProfession", schema="UserItemProfession",
     *  @OA\Property(property="id", type="int", example="ProfessionId"),
     *  @OA\Property(property="name", type="string", example="ProfessionName"),
     * )
     *
     * @param \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'   => $this->id,
            'name' => $this->name,
        ];
    }
}
