<?php

namespace Modules\Curriculums\Transformers;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CurriculumsCollection extends ResourceCollection
{
    public $collects = CurriculumsCollectionItem::class;
    /**
     * @OA\Schema(
     *     title="CurriculumsCollection", schema="CurriculumsCollection",
     *     @OA\Property(
     *      property="data", type="array",
     *      @OA\Items(ref="#/components/schemas/CurriculumsCollectionItem")
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
            'data' => $this->collection,
            'meta' => get_pagination_meta($this)
        ];
    }
}
