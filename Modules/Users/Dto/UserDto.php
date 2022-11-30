<?php

namespace Modules\Users\Dto;

use Spatie\DataTransferObject\DataTransferObject;

class UserDto extends DataTransferObject
{
    protected $exceptKeys = [
        'id',
        'roles',
        'photo',
        'curriculums',
        'profession',
        'is_send_password_to_user'
    ];

    /** @var integer|null */
    public $id;
    /** @var string|null */
    public $email;
    /** @var string|null */
    public $password;
    /** @var string|null */
    public $passport;
    /** @var string|null */
    public $full_name;
    /** @var string|null */
    public $birth_date;
    /** @var string|null */
    public $phone;
    /** @var string|null */
    public $address;
    /** @var string|null */
    public $sex;
    /** @var string|null */
    public $status;
    /** @var int|string|null */
    public $profession;
    /** @var array|null */
    public $roles;
    /** @var string|null */
    public $photo;
    /** @var string|null */
    public $religion;
    /** @var string|null */
    public $religion_type;
    /** @var array|null */
    public $curriculums;
    /** @var bool|integer|string|null */
    public $is_archived;
    /** @var string|null */
    public $zip_code;
    /** @var bool|integer|string|null */
    public $is_send_password_to_user;


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
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @return string|null
     */
    public function getPassport(): ?string
    {
        return $this->passport;
    }

    /**
     * @return string|null
     */
    public function getFullName(): ?string
    {
        return $this->full_name;
    }

    /**
     * @return string|null
     */
    public function getBirthDate(): ?string
    {
        return $this->birth_date;
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
    public function getSex(): ?string
    {
        return $this->sex;
    }

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @return int|string|null
     */
    public function getProfession(): ?string
    {
        return $this->profession;
    }

    /**
     * @return array|null
     */
    public function getRoles(): ?array
    {
        return $this->roles;
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
     * @return array|null
     */
    public function getCurriculums(): ?array
    {
        return $this->curriculums;
    }

    /**
     * @return bool|null
     */
    public function getIsArchived(): ?bool
    {
        return $this->is_archived;
    }

    /**
     * @return string|null
     */
    public function getZipCode(): ?string
    {
        return $this->zip_code;
    }

    /**
     * @return bool|int|string|null
     */
    public function getIsSendPasswordToUser()
    {
        return $this->is_send_password_to_user;
    }
}
