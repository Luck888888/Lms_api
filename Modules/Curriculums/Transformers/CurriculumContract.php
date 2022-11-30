<?php

namespace Modules\Curriculums\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class CurriculumContract extends JsonResource
{
    /**
     * @OA\Schema(title="CurriculumContract", schema="CurriculumContract",
     *  @OA\Property(property="contract_file_url", type="string", example="ContractFileUrl"),
     *  @OA\Property(property="curriculum", type="object",
     *      @OA\Property(property="name", type="string", example="CurriculumName"),
     *  ),
     * )
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'contract_file_url' => optional($this->contractFile)->url,
            'curriculum'        => [
                'name' => $this->name,
            ]

        ];
    }
}
