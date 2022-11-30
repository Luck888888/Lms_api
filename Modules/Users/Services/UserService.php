<?php

namespace Modules\Users\Services;

use Closure;
use ErrorException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Files\Entities\File;
use Modules\Files\Services\FileService;
use Modules\Users\Dto\UserDto;
use Modules\Users\Dto\UserFiltersDto;
use Modules\Users\Entities\User;

class UserService
{
    private FileService $file_service;
    private ?Closure $resource_query_closure = null;

    public function __construct()
    {
        $this->file_service = new FileService(config("users.file_adapter"));
    }

    /**
     * @param \Modules\Users\Dto\UserFiltersDto $filters_dto
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAll(UserFiltersDto $filters_dto): LengthAwarePaginator
    {
        $users_per_page = config("users.users_per_page");
        $users_query = User::query();

        if ($this->resource_query_closure) {
            $closure = $this->resource_query_closure;
            $users_query = $closure($users_query);
        }

        if ($filters_dto->getOrderBy()) {
            foreach ($filters_dto->getOrderBy() as $column => $direction) {
                $users_query->orderBy($column, $direction);
            }
        }

        if ($filters_dto->getFilters()) {
            foreach ($filters_dto->getFilters() as $key => $value) {
                /** TODO тело фильтров курса */
                switch ($key) {
                    case 'roles':
                        $users_query->whereHas("roles", function ($roles_query) use ($value) {
                            $roles_query->whereIn("name", $value);
                        });
                        break;
                    case 'statuses':
                        $users_query->whereIn("status", $value);
                        break;
                    case 'is_archived':
                        $users_query->where("is_archived", $value);
                        break;
                    case 'curriculums':
                        $users_query->whereHas("curriculums", function ($curriculums_query) use ($value) {
                            $curriculums_query->whereIn("curriculums.id", $value);
                        });
                        break;
                    case 'exclude_users':
                        $users_query->whereNotIn("id", $value);
                        break;
                    case 'courses':
                        $users_query->whereHas("courses", function ($courses_query) use ($value) {
                            $courses_query->whereIn("courses.id", $value);
                        });
                        break;
                    case 'religion':
                        $users_query->where("religion", $value);
                        break;
                    case 'religion_type':
                        $users_query->where("religion_type", $value);
                        break;
                    case 'professions':
                        $users_query->whereIn("profession_id", $value);
                        break;
                }
            }
        }

        if ($filters_dto->getSearch()) {
            $search_phrase = $filters_dto->getSearch();
            $users_query->where(function ($search_query) use ($search_phrase) {
                $search_query->where("email", "LIKE", "%" . $search_phrase . "%")
                             ->orWhere("full_name", "LIKE", "%" . $search_phrase . "%");
            });
        }

        return $users_query->paginate($users_per_page);
    }

    /**
     * @param $id
     *
     * @return \Modules\Users\Entities\User
     */
    public function get($id): User
    {
        $user_query = User::query();

        if ($this->resource_query_closure) {
            $closure = $this->resource_query_closure;
            $user_query = $closure($user_query);
        }

        $user = $user_query->where('id', $id)->first();

        if (!$user) {
            throw new ModelNotFoundException("User not found.", 404);
        }

        return $user;
    }

    /**
     * @param \Modules\Users\Dto\UserDto $user_dto
     *
     * @return mixed
     * @throws \ErrorException
     */
    public function create(UserDto $user_dto)
    {
        $user = User::create($user_dto->toArray());

        if (!$user) {
            //TODO Добавить обработку ошибок
            throw new ErrorException('User create error.');
        }

        if (!is_null($user_dto->getRoles())) {
            $user->syncRoles($user_dto->getRoles());
        }

        if (!is_null($user_dto->getPhoto())) {
            $file = $this->uploadPhoto($user_dto->getPhoto(), $user);
            if ($file instanceof File) {
                $user->photo_id = $file->id;
                $user->save();
            }
        }

        if (!is_null($user_dto->getCurriculums())) {
            $user->curriculums()->sync($user_dto->getCurriculums());
        }

        if (!is_null($user_dto->getProfession())) {
            $profession_service = new UsersProfessionService();

            $profession_id = $profession_service->findOrCreate($user_dto->getProfession());
            if ($profession_id) {
                $user->profession_id = $profession_id;
                $user->save();
            }
        }
        return $user->refresh();
    }

    /**
     * @param \Modules\Users\Dto\UserDto $user_dto
     *
     * @return \Modules\Users\Entities\User
     * @throws \ErrorException
     */
    public function update(UserDto $user_dto): User
    {
        $user = $this->get($user_dto->getId());
        // Убираю все поля с значением null чтобы сделать массовое обновление полей
        $updated_data = array_filter($user_dto->toArray(), fn($value) => !is_null($value));
        $user->update($updated_data);

        if (!is_null($user_dto->getRoles())) {
            $user->syncRoles($user_dto->getRoles());
        }

        if (!is_null($user_dto->getPhoto())) {
            if ($user->photo) {
                $this->file_service->delete($user->photo);
            }
            $file = $this->uploadPhoto($user_dto->getPhoto(), $user);
            if ($file instanceof File) {
                $user->photo_id = $file->id;
                $user->save();
            }
        }

        if (!is_null($user_dto->getCurriculums())) {
            $user->curriculums()->sync($user_dto->getCurriculums());
        }

        if (!is_null($user_dto->getProfession())) {
            $profession_service = new UsersProfessionService();

            $profession_id = $profession_service->findOrCreate($user_dto->getProfession());
            if ($profession_id) {
                $user->profession_id = $profession_id;
                $user->save();
            }
        }

        /**
         * Удаляем все токены пользователя если у пользователя забрали доступ в систему
         */
        if (!$user->isUserHasAccess()) {
            $user->tokens()->delete();
        }

        return $user->refresh();
    }

    /**
     * @param $id
     *
     * @return bool|null
     * @throws \Throwable
     */
    public function delete($id): ?bool
    {
        $user = $this->get($id);

        if ($user->photo) {
            $this->file_service->delete($user->photo);
        }

        return $user->deleteOrFail();
    }

    /**
     * @param \Closure $resource_query_closure
     *
     * @return void
     */
    public function setResourceQueryClosure(Closure $resource_query_closure)
    {
        $this->resource_query_closure = $resource_query_closure;
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
