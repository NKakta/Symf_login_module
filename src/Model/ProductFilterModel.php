<?php
declare(strict_types=1);

namespace App\Model;

class ProductFilterModel
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
     * ProductFilterModel constructor.
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
     * @return ProductFilterModel
     */
    public function setPage(?int $page): ProductFilterModel
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
     * @return ProductFilterModel
     */
    public function setLimit(?int $limit): ProductFilterModel
    {
        if (null !== $limit) {
            $this->limit = $limit;
        }

        return $this;
    }

    /**
     * @param string|null $name
     * @return ProductFilterModel
     */
    public function setName(?string $name): ProductFilterModel
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
