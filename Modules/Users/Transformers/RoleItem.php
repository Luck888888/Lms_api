<?php

namespace Modules\Users\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(title="RoleItem", schema="RoleItem",
 *  @OA\Property(property="id", type="int", example="2"),
 *  @OA\Property(property="name", type="string", example="RoleName"),
 * )
 */
class RoleItem extends JsonResource
{

    public function __construct($resource)
    {
        parent::__construct($resource);
    }


    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id"   => $this->id,
            "name" => $this->name,
        ];
    }
}
