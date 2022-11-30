<?php

/**
 * Developed Maxym Rudenko
 * Email: rudenko.programmer@gmail.com
 * Date: 05.01.2022
 */

namespace Modules\Curriculums\Dto;

use Spatie\DataTransferObject\DataTransferObject;

class CurriculumDto extends DataTransferObject
{
    protected $exceptKeys = [
        'id','contract_file'
    ];

    /** @var integer|null */
    public $id;
    /** @var string|null */
    public $name;
    /** @var string|null */
    public $description;
    /** @var string|null */
    public $start_at;
    /** @var string|null */
    public $end_at;
    /** @var \Illuminate\Http\UploadedFile|null */
    public $contract_file;
    /** @var string|null */
    public $years_of_study;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return string|null
     */
    public function getStartAt(): ?string
    {
        return $this->start_at;
    }

    /**
     * @return string|null
     */
    public function getEndAt(): ?string
    {
        return $this->end_at;
    }

    /**
     * @return \Illuminate\Http\UploadedFile|null
     */
    public function getContractFile(): ?\Illuminate\Http\UploadedFile
    {
        return $this->contract_file;
    }

    /**
     * @return string|null
     */
    public function getYearsOfStudy(): ?string
    {
        return $this->years_of_study;
    }
}
