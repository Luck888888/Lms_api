<?php

namespace Modules\Users\Dto;

use Spatie\DataTransferObject\DataTransferObject;

class ProfileDto extends DataTransferObject
{
    protected $exceptKeys = [
        'id',
        'photo'
    ];

    /** @var integer|null */
    public $id;
    /** @var string|null */
    public $email;
    /** @var string|null */
    public $phone;
    /** @var string|null */
    public $address;
    /** @var string|null */
    public $photo;
    /** @var string|null */
    public $religion;
    /** @var string|null */
    public $religion_type;
    /** @var string|null */
    public $zip_code;

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
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @return string|null
     */
    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    /**
     * @return string|null
     */
    public function getReligion(): ?string
    {
        return $this->religion;
    }

    /**
     * @return string|null
     */
    public function getReligionType(): ?string
    {
        return $this->religion_type;
    }

    /**
     * @return string|null
     */
    public function getZipCode(): ?string
    {
        return $this->zip_code;
    }
}
