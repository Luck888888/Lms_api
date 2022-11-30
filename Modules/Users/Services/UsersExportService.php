<?php

namespace Modules\Users\Services;

use Closure;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Users\Dto\UserExportDto;
use Modules\Users\Entities\User;
use Modules\Users\Entities\UsersExportCollection;

class UsersExportService
{
    use Exportable;

    private ?Closure $resource_query_closure = null;

    /**
     * @param \Modules\Users\Dto\UserExportDto $filters_dto
     *
     */
    public function getAll(UserExportDto $filters_dto)
    {
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

        return $users_query->get();
    }

    /**
     * @param \Modules\Users\Dto\UserExportDto $filters_dto
     *
     * @return string|null
     */
    public function generateExportFile(UserExportDto $filters_dto): ?string
    {
        $adapter = "temp";

        if ($filters_dto->getFileFormat() == null) {
            $file_name = Str::uuid() . '.csv';
        } else {
            $file_name = Str::uuid() . '.' . $filters_dto->file_format;
        }
        $users_collection = $this->getAll($filters_dto);
        $result = Excel::store(new UsersExportCollection($users_collection), $file_name, $adapter);

        if ($result) {
            return get_file_url('temp', "", $file_name);
        }

        return null;
    }
}
