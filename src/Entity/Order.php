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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
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
     */
    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
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
     */
    public function setUpdatedAt(\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
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
     */
    public function setPrice(?string $price): void
    {
        $this->price = $price;
    }

    /**
     * @param mixed $transactionId
     * @return Order
     */
    public function setTransactionId($transactionId)
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
     * @param mixed $quantity
     * @return Order
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param mixed $method
     * @return Order
     */
    public function setMethod($method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMethod()
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
    public function setPayerEmail($payerEmail)
    {
        $this->payerEmail = $payerEmail;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPayerEmail()
    {
        return $this->payerEmail;
    }

    /**
     * @param mixed $paymentStatus
     * @return Order
     */
    public function setPaymentStatus($paymentStatus)
    {
        $this->paymentStatus = $paymentStatus;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPaymentStatus()
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


}
