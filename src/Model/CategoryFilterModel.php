<?php
declare(strict_types=1);

namespace App\Model;

class CategoryFilterModel
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
    private $name;

    /**
     * CategoryFilterModel constructor.
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
     * @return CategoryFilterModel
     */
    public function setPage(?int $page): CategoryFilterModel
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
     * @return CategoryFilterModel
     */
    public function setLimit(?int $limit): CategoryFilterModel
    {
        if (null !== $limit) {
            $this->limit = $limit;
        }

        return $this;
    }

    /**
     * @param string|null $name
     * @return CategoryFilterModel
     */
    public function setName(?string $name): CategoryFilterModel
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }
}
