<?php
declare(strict_types=1);

namespace App\Model;

class AccountFilterModel
{
    /**
     * @var int|null
     */
    protected $page;

    /**
     * @var int|null
     */
    protected $limit;

    /**
     * @var string|null
     */
    private $username;

    /**
     * AccountFilterModel constructor.
     */
    public function __construct()
    {
        $this->page = 1;
        $this->limit = 20;
    }

    /**
     * @return int|null
     */
    public function getPage(): ?int
    {
        return $this->page;
    }

    /**
     * Set page.
     *
     * @param int|null $page
     * @return AccountFilterModel
     */
    public function setPage(?int $page): AccountFilterModel
    {
        if (null !== $page) {
            $this->page = $page;
        }

        return $this;
    }

    /**
     * @return int|null
     */
    public function getLimit(): ?int
    {
        return $this->limit;
    }

    /**
     * Set limit.
     *
     * @param int|null $limit
     * @return AccountFilterModel
     */
    public function setLimit(?int $limit): AccountFilterModel
    {
        if (null !== $limit) {
            $this->limit = $limit;
        }

        return $this;
    }

    /**
     * @param string|null $username
     * @return AccountFilterModel
     */
    public function setUsername(?string $username): AccountFilterModel
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }
}
