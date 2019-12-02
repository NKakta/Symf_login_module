<?php
declare(strict_types=1);

namespace App\Model;

use App\Entity\Product;

class PaymentModel
{
    /**
     * @var string|null
     */
    protected $paymentMethod;

    /**
     * @var int|null
     */
    protected $quantity;

    /**
     * @var int|null
     */
    private $inStock;

    /**
     * @var float|null
     */
    private $totalPrice;

    /**
     * @var float|null
     */
    private $originalPrice;

    /**
     * @var Product|null
     */
    private $productId;

    /**
     * @param string|null $paymentMethod
     * @return PaymentModel
     */
    public function setPaymentMethod(?string $paymentMethod): PaymentModel
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPaymentMethod(): ?string
    {
        return $this->paymentMethod;
    }

    /**
     * @param int|null $quantity
     * @return PaymentModel
     */
    public function setQuantity(?int $quantity): PaymentModel
    {
        $this->quantity = $quantity;

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
     * @param int|null $inStock
     * @return PaymentModel
     */
    public function setInStock(?int $inStock): PaymentModel
    {
        $this->inStock = $inStock;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getInStock(): ?int
    {
        return $this->inStock;
    }

    /**
     * @param float|null $totalPrice
     * @return PaymentModel
     */
    public function setTotalPrice(?float $totalPrice): PaymentModel
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getTotalPrice(): ?float
    {
        return $this->totalPrice;
    }

    /**
     * @param float|null $originalPrice
     * @return PaymentModel
     */
    public function setOriginalPrice(?float $originalPrice): PaymentModel
    {
        $this->originalPrice = $originalPrice;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getOriginalPrice(): ?float
    {
        return $this->originalPrice;
    }

    /**
     * @param Product|null $productId
     * @return PaymentModel
     */
    public function setProductId(?Product $productId): PaymentModel
    {
        $this->productId = $productId;

        return $this;
    }

    /**
     * @return Product|null
     */
    public function getProductId(): ?Product
    {
        return $this->productId;
    }

    /**
     * @return float|null
     */
    public function getPrice(): float
    {
        return $this->getQuantity() * $this->getProductId()->getPrice();
    }

}
