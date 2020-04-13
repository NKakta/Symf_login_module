<?php
declare(strict_types=1);

namespace App\Model;

use App\Entity\Product;

class StoredItem
{
    public $quantity;

    public $price;

    public $item;

    public function __construct(int $quantity = 0, float $price = 0, ?Product $item = null)
    {
        $this->quantity = $quantity;
        $this->price = $price;
        $this->item = $item;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     * @return StoredItem
     */
    public function setQuantity(int $quantity): StoredItem
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param float $price
     * @return StoredItem
     */
    public function setPrice(float $price): StoredItem
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return Product|null
     */
    public function getItem(): ?Product
    {
        return $this->item;
    }

    /**
     * @param Product|null $item
     * @return StoredItem
     */
    public function setItem(?Product $item): StoredItem
    {
        $this->item = $item;
        return $this;
    }

    public function incrementQuantity()
    {
        $this->quantity++;
    }

    public function removeOneItem()
    {
        $this->quantity--;
        $this->price -= $this->item->getPrice();
    }
}
