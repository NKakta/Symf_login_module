<?php
declare(strict_types=1);

namespace App\Model;

use App\Entity\Product;
use App\Exception\NotEnoughInStockException;

class Cart implements \Serializable
{
    /**
     * @var array<StoredItem>
     */
    public $items;

    /**
     * @var int
     */
    public $totalQuantity;

    /**
     * @var float
     */
    public $totalPrice;

    /**
     * Cart constructor.
     */
    public function __construct()
    {
        $this->items = [];
        $this->totalQuantity = 0;
        $this->totalPrice = 0;
    }

    /**
     * @return array<StoredItem>
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param array<StoredItem> $items
     * @return Cart
     */
    public function setItems(array $items): ?Cart
    {
        $this->items = $items;
        return $this;
    }

    /**
     * @return int
     */
    public function getTotalQuantity(): int
    {
        return $this->totalQuantity;
    }

    /**
     * @param int $totalQuantity
     * @return Cart
     */
    public function setTotalQuantity(int $totalQuantity): ?Cart
    {
        $this->totalQuantity = $totalQuantity;
        return $this;
    }

    /**
     * @return float
     */
    public function getTotalPrice(): float
    {
        return $this->totalPrice;
    }

    /**
     * @param float $totalPrice
     * @return Cart
     */
    public function setTotalPrice(float $totalPrice): ?Cart
    {
        $this->totalPrice = $totalPrice;
        return $this;
    }

    /**
     * @param Product $product
     * @throws NotEnoughInStockException
     */
    public function add(Product $product)
    {
        $storedItem = $this->getIfExists($product);

        $storedItem->incrementQuantity();
        $storedItem->setPrice($product->getPrice() * $storedItem->getQuantity());

        $this->items[$storedItem->getItem()->getId()] = $storedItem;
        $this->totalQuantity++;
        $this->totalPrice += $product->getPrice();

        try {
            $this->validate();
        } catch (NotEnoughInStockException $e) {
            throw $e;
        }
    }

    protected function getIfExists(Product $product): StoredItem
    {
        if (isset($this->items[$product->getId()]) && $this->items[$product->getId()] instanceof StoredItem) {
            return $this->items[$product->getId()];
        }

        return new StoredItem(0, (float)$product->getPrice(), $product);
    }

    /**
     * @param Product $product
     * @throws NotEnoughInStockException
     */
    public function reduceByOne(Product $product)
    {
        $storedItem = $this->getIfExists($product);

        $storedItem->removeOneItem();

        $this->totalQuantity--;
        $this->totalPrice -= (float)$product->getPrice();

        if (isset($this->items[$product->getId()]) && $this->items[$product->getId()]->getQuantity() <= 0) {
            unset($this->items[$product->getId()]);
        }

        try {
            $this->validate();
        } catch (NotEnoughInStockException $e) {
            throw $e;
        }
    }

    public function removeItem(Product $product)
    {
        $storedItem = $this->getIfExists($product);

        $this->totalQuantity -= $storedItem->getQuantity();
        $this->totalPrice -= $product->getPrice() * $storedItem->getQuantity();

        if (isset($this->items[$product->getId()])) {
            unset($this->items[$product->getId()]);
        }
    }

    public function validate()
    {
        $found= [];
        /* @var StoredItem $item */
        foreach ($this->items as $item) {
            $product = $item->getItem();
            if ($item->getQuantity() > $product->getCalculatedQuantity() || $item->getQuantity() < 0) {
                $this->removeItem($product);
                $found []= $product->getName();
            }
        }

        if (!empty($found)) {
            throw new NotEnoughInStockException('Not enough of ' . implode(', ', $found));
        }
    }

    public function getProducts()
    {
        $products = [];
        foreach ($this->items as $item) {
            $products []= $item->getItem();
        }
        return $products;
    }

    public function serialize()
    {
        return serialize(
            [
                $this->items,
                $this->totalQuantity,
                $this->totalPrice,
            ]
        );
    }

    public function unserialize($serialized)
    {
        $data = unserialize($serialized);
        list(
            $this->items,
            $this->totalQuantity,
            $this->totalPrice,
            ) = $data;
    }
}
