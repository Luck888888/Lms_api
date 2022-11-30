<?php

namespace Modules\Users\Dto;

use Spatie\DataTransferObject\DataTransferObject;

class UserFiltersDto extends DataTransferObject
{
    /** @var string|integer|null */
    public $page;
    /** @var string|null */
    public $search;
    /** @var array|null */
    public $order_by;
    /** @var array|null */
    public $filters;
    /** @var \Modules\Users\Entities\User|null */
    public ?\Modules\Users\Entities\User $user;

    /**
     * @return int|string|null
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @return array|null
     */
    public function getOrderBy(): ?array
    {
        return $this->order_by;
    }

    /**
     * @return array|null
     */
    public function getFilters(): ?array
    {
        return $this->filters;
    }

    /**
     * @return \Modules\Users\Entities\User|null
     */
    public function getUser(): ?\Modules\Users\Entities\User
    {
        return $this->user;
    }

    /**
     * @return string|null
     */
    public function getSearch(): ?string
    {
        return $this->search;
    }
}
