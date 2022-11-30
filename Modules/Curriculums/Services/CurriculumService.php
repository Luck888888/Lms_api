<?php

namespace Modules\Curriculums\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Modules\Curriculums\Dto\CurriculumDto;
use Modules\Curriculums\Dto\CurriculumFiltersDto;
use Modules\Curriculums\Entities\Curriculum;
use Modules\Files\Entities\File;
use Modules\Files\Services\FileService;

class CurriculumService
{
    private $file_path;
    private FileService $file_service;

    public function __construct()
    {
        $this->file_path = config("curriculums.file_path");
        $this->file_service = new FileService();
    }
    /**
     * @return mixed
     */
    public function getAll(CurriculumFiltersDto $filters_dto): LengthAwarePaginator
    {
        $curriculums_per_page = config("curriculums.curriculums_per_page");
        $curriculums_query = Curriculum::query();

        if ($filters_dto->getUser()) {
            $user = $filters_dto->getUser();
            $roles = optional($user->roles)->pluck('name')->toArray() ?? [];
            if (!empty($roles)) {
                $auth_user_id = $user->id;
                if (in_array('administrator', $roles)) {
                    //TODO do nothing
                } elseif (in_array('teacher', $roles)) {
                    $curriculums_query->whereHas('courses', function (Builder $query) use ($auth_user_id) {
                        $query->whereHas('teachers', function (Builder $user_query) use ($auth_user_id) {
                            $user_query->where('users.id', $auth_user_id);
                        });
                    });
                } elseif (in_array('student', $roles)) {
                    $curriculums_query->whereHas('students', function (Builder $query) use ($auth_user_id) {
                        $query->where('users.id', $auth_user_id);
                    });
                }
            }
        }

        if ($filters_dto->getOrderBy()) {
            foreach ($filters_dto->getOrderBy() as $column => $direction) {
                $curriculums_query->orderBy($column, $direction);
            }
        }

        if ($filters_dto->getFilters()) {
            foreach ($filters_dto->getFilters() as $key => $value) {
                /** TODO тело фильтров курса */
            }
        }

        if ($filters_dto->getSearch()) {
            $search_phrase = $filters_dto->getSearch();
            $curriculums_query->where(function ($search_query) use ($search_phrase) {
                $search_query->where("name", "LIKE", "%" . $search_phrase . "%")
                    ->orWhere("description", "LIKE", "%" . $search_phrase . "%");
            });
        }

        $curriculums = $curriculums_query->paginate($curriculums_per_page);

        return $curriculums;
    }

    /**
     * @param $id
     *
     * @return Curriculum
     * @throws
     */
    public function get($curriculum_id): Curriculum
    {
        $curriculum = Curriculum::where('curriculums.id', $curriculum_id)
                                ->with(['students' => function ($query) use ($curriculum_id) {
                                    $query->select(['users.id','email','full_name','photo_id','phone']);
                                    $query->with(['contracts'  => function ($contract_query) use ($curriculum_id) {
                                        $contract_query->where('contractable_id', $curriculum_id);
                                        $contract_query->where('contractable_type', Curriculum::class);
                                    }]);
                                }])->first();
        if (!$curriculum) {
            throw new ModelNotFoundException("Curriculum not found.", 404);
        }

        return $curriculum;
    }

    /**
     * @param \Modules\Curriculums\Dto\CurriculumDto $curriculum_dto
     *
     * @return mixed
     */
    public function create(CurriculumDto $curriculum_dto)
    {
        $curriculum = Curriculum::create($curriculum_dto->toArray());

        $this->uploadCurriculumContracts($curriculum, $curriculum_dto);

        return $curriculum->refresh();
    }

    /**
     * @param \Modules\Curriculums\Dto\CurriculumDto $curriculum_dto
     *
     * @return \Modules\Curriculums\Entities\Curriculum
     */
    public function update(CurriculumDto $curriculum_dto)
    {
        $curriculum = $this->get($curriculum_dto->getId());
        $curriculum->update($curriculum_dto->toArray());

        $this->uploadCurriculumContracts($curriculum, $curriculum_dto, true);

        return $curriculum->refresh();
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function delete($id)
    {
        $curriculum = $this->get($id);
        return $curriculum->deleteOrFail();
    }


    /**
     * @param \Modules\Curriculums\Entities\Curriculum $curriculum
     * @param \Modules\Curriculums\Dto\CurriculumDto $curriculum_dto
     * @param false $is_delete_old_files
     *
     * @return void
     */
    private function uploadCurriculumContracts(Curriculum $curriculum, CurriculumDto $curriculum_dto, bool $is_delete_old_files = false): void
    {
        if ($curriculum_dto->getContractFile()) {
            if ($curriculum->contractFile && $is_delete_old_files) {
                $this->file_service->delete($curriculum->contractFile);
                $curriculum->contract_file_id = null;
            }
            $title = __('Curriculum :curriculum_id contract', ['curriculum_id' => $curriculum->id]);
            $contract_file = $this->file_service->upload($curriculum_dto->getContractFile(), $title, $this->file_path);
            if ($contract_file instanceof File) {
                $curriculum->contract_file_id = $contract_file->id;
            }
        }
        $curriculum->save();
    }
}
