<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="orders")
 * @ORM\Entity(repositoryClass="App\Repository\OrderRepository")
 */
class Order
{
    const PAYMENT_PENDING = 'pending';
    const PAYMENT_COMPLETED = 'finished';
    const PAYMENT_CANCELED = 'canceled';

    const TYPE_PAYMENT_CRYPTO = 'crypto';
    const TYPE_PAYMENT_PAYPAL = 'paypal';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @ORM\Column(name="transaction_id", type="string", length=255, nullable=true)
     */
    private $transactionId;

    /**
     * @var Product|null
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="orders")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     *
     */
    private $product;

    /**
     * @ORM\Column(name="quantity", type="integer", nullable=false)
     */
    private $quantity;

    /**
     * @ORM\Column(name="payer_email", type="string", length=255, nullable=true)
     */
    private $payerEmail;

    /**
     * @ORM\Column(name="payment_status", type="string", length=255, unique=false)
     */
    private $paymentStatus;

    /**
     * @var string|null
     *
     * @ORM\Column(name="price", type="decimal", precision=19, scale=2, nullable=false)
     *
     */
    private $price;

    /**
     * @ORM\Column(name="method", type="string", length=255, unique=false, nullable=false)
     */
    private $method;

    /**
     * @ORM\Column(name="region", type="string", length=255, unique=false, nullable=false)
     */
    private $region;

    /**
     * @var bool
     *
     * @ORM\Column(name="sold", type="boolean", nullable=true)
     */
    private $sold = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    /**
     * @return mixed
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Order
     */
    public function setId(string $id): Order
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
     * @return Order
     */
    public function setName(?string $name): Order
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     * @return Order
     */
    public function setCreatedAt(\DateTime $createdAt): Order
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     * @return Order
     */
    public function setUpdatedAt(\DateTime $updatedAt): Order
    {
        $this->updatedAt = $updatedAt;
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
     * @return Order
     */
    public function setPrice(?string $price): Order
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @param string $transactionId
     * @return Order
     */
    public function setTransactionId(string $transactionId): Order
    {
        $this->transactionId = $transactionId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    /**
     * @param Product|null $product
     * @return Order
     */
    public function setProduct(?Product $product): Order
    {
        $this->product = $product;
        return $this;
    }

    /**
     * @return Product|null
     */
    public function getProduct(): ?Product
    {
        return $this->product;
    }

    /**
     * @param int $quantity
     * @return Order
     */
    public function setQuantity(int $quantity): Order
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @param string $method
     * @return Order
     */
    public function setMethod(string $method): Order
    {
        $this->method = $method;
        return $this;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param bool $sold
     * @return Order
     */
    public function setSold(bool $sold): Order
    {
        $this->sold = $sold;
        return $this;
    }

    /**
     * @return bool
     */
    public function isSold(): bool
    {
        return $this->sold;
    }

    /**
     * @param mixed $payerEmail
     * @return Order
     */
    public function setPayerEmail(?string $payerEmail): Order
    {
        $this->payerEmail = $payerEmail;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPayerEmail(): ?string
    {
        return $this->payerEmail;
    }

    /**
     * @param string $paymentStatus
     * @return Order
     */
    public function setPaymentStatus(string $paymentStatus): Order
    {
        $this->paymentStatus = $paymentStatus;
        return $this;
    }

    /**
     * @return string
     */
    public function getPaymentStatus(): string
    {
        return $this->paymentStatus;
    }

    /**
     * @return float
     */
    public function getTotalPrice(): float
    {
        return $this->getQuantity() * $this->getProduct()->getPrice();
    }

    /**
     * @return string
     */
    public function getRegion(): string
    {
        return $this->region;
    }

    /**
     * @param string $region
     * @return Order
     */
    public function setRegion(string $region): Order
    {
        $this->region = $region;
        return $this;
    }
}
