<?php
declare(strict_types=1);

namespace App\Model;

use JMS\Serializer\Annotation as Serializer;

class Champion
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
     * @var string
     * @Serializer\Type("string")
     * @Serializer\SerializedName("DisplayName")
     */
    private $displayName;

    /**
     * @var string
     * @Serializer\Type("string")
     * @Serializer\SerializedName("PurchaseDate")
     */
    private $purchaseDate;

    /**
     * @var int
     * @Serializer\Type("int")
     * @Serializer\SerializedName("Skins")
     */
    private $skins;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Champion
     */
    public function setId(int $id): Champion
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
     * @return Champion
     */
    public function setName(string $name): Champion
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getDisplayName(): string
    {
        return $this->displayName;
    }

    /**
     * @param string $displayName
     * @return Champion
     */
    public function setDisplayName(string $displayName): Champion
    {
        $this->displayName = $displayName;
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
     * @return Champion
     */
    public function setPurchaseDate(string $purchaseDate): Champion
    {
        $this->purchaseDate = $purchaseDate;
        return $this;
    }

    /**
     * @return int
     */
    public function getSkins(): int
    {
        return $this->skins;
    }

    /**
     * @param int $skins
     * @return Champion
     */
    public function setSkins(int $skins): Champion
    {
        $this->skins = $skins;
        return $this;
    }


}
