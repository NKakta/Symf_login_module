<?php
declare(strict_types=1);

namespace App\Model;

use JMS\Serializer\Annotation as Serializer;

class Skin
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
     * @Serializer\SerializedName("Name")
     */
    private $name;

    /**
     * @var int
     * @Serializer\Type("int")
     * @Serializer\SerializedName("Num")
     */
    private $num;

    /**
     * @var boolean
     * @Serializer\Type("boolean")
     * @Serializer\SerializedName("IsLegendary")
     */
    private $isLegendary;

    /**
     * @var boolean
     * @Serializer\Type("boolean")
     * @Serializer\SerializedName("IsUltimate")
     */
    private $isUltimate;

    /**
     * @var boolean
     * @Serializer\Type("boolean")
     * @Serializer\SerializedName("IsUltraRare")
     */
    private $isUltraRare;

    /**
     * @var boolean
     * @Serializer\Type("boolean")
     * @Serializer\SerializedName("StillObtainable")
     */
    private $stillObtainable;

    /**
     * @var string
     * @Serializer\Type("string")
     * @Serializer\SerializedName("PurchaseDate")
     */
    private $purchaseDate;

    /**
     * @var int
     * @Serializer\Type("int")
     * @Serializer\SerializedName("ChampionId")
     */
    private $championId;

    /**
     * @var string
     * @Serializer\Type("string")
     * @Serializer\SerializedName("ChampionName")
     */
    private $championName;

    /**
     * @var string
     * @Serializer\Type("string")
     * @Serializer\SerializedName("ChampionDisplayName")
     */
    private $championDisplayName;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Skin
     */
    public function setId(int $id): Skin
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Skin
     */
    public function setName(string $name): Skin
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return int
     */
    public function getNum(): int
    {
        return $this->num;
    }

    /**
     * @param int $num
     * @return Skin
     */
    public function setNum(int $num): Skin
    {
        $this->num = $num;
        return $this;
    }

    /**
     * @return bool
     */
    public function isLegendary(): bool
    {
        return $this->isLegendary;
    }

    /**
     * @param bool $isLegendary
     * @return Skin
     */
    public function setIsLegendary(bool $isLegendary): Skin
    {
        $this->isLegendary = $isLegendary;
        return $this;
    }

    /**
     * @return bool
     */
    public function isUltimate(): bool
    {
        return $this->isUltimate;
    }

    /**
     * @param bool $isUltimate
     * @return Skin
     */
    public function setIsUltimate(bool $isUltimate): Skin
    {
        $this->isUltimate = $isUltimate;
        return $this;
    }

    /**
     * @return bool
     */
    public function isUltraRare(): bool
    {
        return $this->isUltraRare;
    }

    /**
     * @param bool $isUltraRare
     * @return Skin
     */
    public function setIsUltraRare(bool $isUltraRare): Skin
    {
        $this->isUltraRare = $isUltraRare;
        return $this;
    }

    /**
     * @return bool
     */
    public function isStillObtainable(): bool
    {
        return $this->stillObtainable;
    }

    /**
     * @param bool $stillObtainable
     * @return Skin
     */
    public function setStillObtainable(bool $stillObtainable): Skin
    {
        $this->stillObtainable = $stillObtainable;
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
     * @return Skin
     */
    public function setPurchaseDate(string $purchaseDate): Skin
    {
        $this->purchaseDate = $purchaseDate;
        return $this;
    }

    /**
     * @return int
     */
    public function getChampionId(): int
    {
        return $this->championId;
    }

    /**
     * @param int $championId
     * @return Skin
     */
    public function setChampionId(int $championId): Skin
    {
        $this->championId = $championId;
        return $this;
    }

    /**
     * @return string
     */
    public function getChampionName(): string
    {
        return $this->championName;
    }

    /**
     * @param string $championName
     * @return Skin
     */
    public function setChampionName(string $championName): Skin
    {
        $this->championName = $championName;
        return $this;
    }

    /**
     * @return string
     */
    public function getChampionDisplayName(): string
    {
        return $this->championDisplayName;
    }

    /**
     * @param string $championDisplayName
     * @return Skin
     */
    public function setChampionDisplayName(string $championDisplayName): Skin
    {
        $this->championDisplayName = $championDisplayName;
        return $this;
    }


}
