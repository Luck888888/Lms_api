<?php

namespace Modules\Users\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(title="LoginUser", schema="LoginUserItem",
 *  @OA\Property(property="email", type="string", format="email", example="test@test.com"),
 *  @OA\Property(property="photo_url", type="string", example="PhotoUrl"),
 *  @OA\Property(property="full_name", type="string", example="UserFullName"),
 *  @OA\Property(property="access_token", type="string", example="AccessToken"),
 *  @OA\Property(property="roles", type="array", @OA\Items(type="string", example="RoleName"))
 * )
 */
class LoginUserItem extends JsonResource
{
    private $token;

    public function __construct($resource, $token)
    {
        parent::__construct($resource);
        $this->token = $token;
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
            "email"        => $this->email,
            'photo_url'    => optional($this->photo)->url,
            "full_name"    => $this->full_name,
            "access_token" => $this->token,
            "roles"        => optional($this->roles)->pluck("name")->toArray() ?? []
        ];
    }
}
