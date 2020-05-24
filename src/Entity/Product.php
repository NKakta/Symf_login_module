<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="products")
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=191, unique=false)
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @var float|null
     *
     * @ORM\Column(name="price", type="decimal", precision=19, scale=2, nullable=false)
     */
    private $price;

    /**
     * @var int|null
     *
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @var int|null
     *
     * @ORM\Column(type="integer")
     */
    private $reservedQuantity;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=191)
     */
    private $type;

    /**
     * @ORM\ManyToMany(targetEntity="Order", mappedBy="products", cascade={"persist"})
     */
    private $orders;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
        $this->reservedQuantity = 0;
    }

    /**
     * @return mixed
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Product
     */
    public function setId($id): Product
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return Product
     */
    public function setName($name): Product
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPrice(): ?string
    {
        return $this->price;
    }

    /**
     * @param string|null $price
     * @return Product
     */
    public function setPrice(?string $price): Product
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    /**
     * @param int|null $quantity
     * @return Product
     */
    public function setQuantity(?int $quantity): Product
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     * @return Product
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     * @return Product
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getReservedQuantity(): ?int
    {
        return $this->reservedQuantity;
    }

    /**
     * @param int|null $reservedQuantity
     */
    public function setReservedQuantity(?int $reservedQuantity): void
    {
        $this->reservedQuantity = $reservedQuantity;
    }

    public function getCalculatedQuantity(): int
    {
        return $this->quantity - $this->reservedQuantity;
    }

    /**
     * @return mixed
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * @param mixed $orders
     * @return Product
     */
    public function setOrders($orders)
    {
        $this->orders = $orders;
        return $this;
    }
}
