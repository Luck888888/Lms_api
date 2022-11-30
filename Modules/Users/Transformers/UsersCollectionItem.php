<?php

namespace Modules\Users\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class UsersCollectionItem extends JsonResource
{
    /**
     * @OA\Schema(title="UsersCollectionItem", schema="UsersCollectionItem",
     *  @OA\Property(property="id", type="int", example="UserId"),
     *  @OA\Property(property="photo_url", type="string", example="PhotoUrl"),
     *  @OA\Property(property="full_name", type="string", example="UserFullName"),
     *  @OA\Property(property="sex", type="string", example="UserSex"),
     *  @OA\Property(property="email", type="string", example="UserEmail"),
     *  @OA\Property(property="roles", type="array", @OA\Items(type="string", example="RoleName")),
     *  @OA\Property(property="status", type="string", example="UserStatus"),
     *  @OA\Property(property="is_archived", type="boolean", example="false"),
     *  @OA\Property(
     *   property="curriculums", type="array",
     *   @OA\Items(ref="#/components/schemas/UserItemCurriculum")
     *  ),
     *  @OA\Property(property="created_at", type="string", example="2021-10-11 14:52:25")
     * )
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id"          => $this->id,
            'photo_url'   => optional($this->photo)->url,
            "full_name"   => $this->full_name,
            "sex"         => $this->sex,
            "email"       => $this->email,
            "roles"       => optional($this->roles)->pluck("name")->toArray() ?? [],
            "status"      => $this->status,
            'is_archived' => $this->is_archived,
            'curriculums' => UserItemCurriculum::collection($this->whenLoaded('curriculums')),
            "created_at"  => optional($this->created_at)->format(get_date_time_format()),
        ];
    }
}
