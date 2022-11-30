<?php

namespace Modules\Users\Services;

use Closure;
use ErrorException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Files\Entities\File;
use Modules\Files\Services\FileService;
use Modules\Users\Dto\ProfileDto;
use Modules\Users\Dto\UserDto;
use Modules\Users\Dto\UserFiltersDto;
use Modules\Users\Entities\User;

class ProfileService
{
    private FileService $file_service;

    public function __construct()
    {
        $this->file_service = new FileService(config("users.file_adapter"));
    }

    /**
     * @param $id
     *
     * @return \Modules\Users\Entities\User
     */
    public function get($id): User
    {
        $user_query = User::query();
        $user = $user_query->where('id', $id)->first();

        if (!$user) {
            throw new ModelNotFoundException("User not found.", 404);
        }

        return $user;
    }

    /**
     * @param \Modules\Users\Dto\UserDto $profile_dto
     *
     * @return \Modules\Users\Entities\User
     */
    public function update(ProfileDto $profile_dto): User
    {
        $user = $this->get($profile_dto->getId());
        $updated_data = array_filter($profile_dto->toArray(), fn($value) => !is_null($value));
        $user->update($updated_data);

        if (!is_null($profile_dto->getPhoto())) {
            if ($user->photo) {
                $this->file_service->delete($user->photo);
            }
            $file = $this->uploadPhoto($profile_dto->getPhoto(), $user);
            if ($file instanceof File) {
                $user->photo_id = $file->id;
                $user->save();
            }
        }

        return $user->refresh();
    }


    /**
     * @param $base64image
     * @param \Modules\Users\Entities\User $user
     *
     * @return false|File
     */
    private function uploadPhoto($base64image, User $user)
    {
        $title = 'User #' . $user->id . ' ' . $user->full_name . ' avatar';
        return $this->file_service->uploadBase64($base64image, $title);
    }
}
