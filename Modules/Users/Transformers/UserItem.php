<?php

namespace Modules\Users\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Curriculums\Entities\Curriculum;

class UserItem extends JsonResource
{
    /**
     * @OA\Schema(title="UserItem", schema="UserItem",
     *  @OA\Property(property="id", type="integer", example="UserId"),
     *  @OA\Property(property="email", type="string", format="email", example="UserEmail"),
     *  @OA\Property(property="passport", type="string", example="UserPassportNumber"),
     *  @OA\Property(property="photo_url", type="string", example="PhotoUrl"),
     *  @OA\Property(property="full_name", type="string", example="FullName"),
     *  @OA\Property(property="birth_date", type="string", format="date", example="2021-07-21"),
     *  @OA\Property(property="phone", type="string", example="+14155550132"),
     *  @OA\Property(property="address", type="string", example="User Address"),
     *  @OA\Property(property="sex", type="string", example="UserSex"),
     *  @OA\Property(property="status", type="string", example="UserStatus"),
     *  @OA\Property(property="profession", type="string|int", example="User Profession"),
     *  @OA\Property(property="is_archived", type="boolean", example="false"),
     *  @OA\Property(property="roles", type="array", @OA\Items(type="string", example="administrator")),
     *  @OA\Property(property="religion", type="string", example="ReligionKey"),
     *  @OA\Property(property="religion_type", type="string", example="ReligionType"),
     *  @OA\Property(property="zip_code", type="string", example="Post Index"),
     *  @OA\Property(
     *   property="curriculums", type="array",
     *   @OA\Items(ref="#/components/schemas/UserItemCurriculum")
     *  ),
     * )
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'email'         => $this->email,
            'passport'      => $this->passport,
            'photo_url'     => optional($this->photo)->url,
            'full_name'     => $this->full_name,
            'birth_date'    => optional($this->birth_date)->format(get_date_time_format()),
            'phone'         => $this->phone,
            'address'       => $this->address,
            'sex'           => $this->sex,
            'status'        => $this->status,
            'profession'    => new UserItemProfession($this->profession),
            'is_archived'   => $this->is_archived,
            'roles'         => optional($this->roles)->pluck("name")->toArray() ?? [],
            'religion'      => $this->religion,
            'religion_type' => $this->religion_type,
            'zip_code'      => $this->zip_code,
            'curriculums'   => UserItemCurriculum::collection($this->curriculums),
        ];
    }
}
