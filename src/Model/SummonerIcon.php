<?php
declare(strict_types=1);

namespace App\Model;

use JMS\Serializer\Annotation as Serializer;

class SummonerIcon
{
    /**
     * @var int
     * @Serializer\Type("int")
     * @Serializer\SerializedName("Id")
     */
    private $id;

    /**
     * @var string
     * @Serializer\Type("string")
     * @Serializer\SerializedName("PurchaseDate")
     */
    private $purchaseDate;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return SummonerIcon
     */
    public function setId(int $id): SummonerIcon
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getPurchaseDate(): string
    {
        return $this->purchaseDate;
    }

    /**
     * @param string $purchaseDate
     * @return SummonerIcon
     */
    public function setPurchaseDate(string $purchaseDate): SummonerIcon
    {
        $this->purchaseDate = $purchaseDate;
        return $this;
    }


}
