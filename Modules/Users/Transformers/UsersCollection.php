<?php

namespace Modules\Users\Transformers;

use Illuminate\Http\Resources\Json\ResourceCollection;

class UsersCollection extends ResourceCollection
{
    /**
     * @OA\Schema(
     *     title="UsersCollection", schema="UsersCollection",
     *     @OA\Property(
     *      property="data", type="array",
     *      @OA\Items(ref="#/components/schemas/UsersCollectionItem")
     *     ),
     *     @OA\Property(property="meta", ref="#/components/schemas/ResourcePaginable")
     * )
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => UsersCollectionItem::collection($this->collection),
            'meta' => get_pagination_meta($this)
        ];
    }
}
