<?php
declare(strict_types=1);

namespace App\Model\Import\ConcreteChecker;

use JMS\Serializer\Annotation as Serializer;


class PayloadRegion
{
    /**
     * @var string
     * @Serializer\Type("string")
     * @Serializer\SerializedName("Name")
     */
    protected $name;

    /**
     * @var string
     * @Serializer\Type("string")
     * @Serializer\SerializedName("Short")
     */
    protected $short;

    /**
     * @var string
     * @Serializer\Type("string")
     * @Serializer\SerializedName("Code")
     */
    protected $code;

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return PayloadRegion
     */
    public function setName(?string $name): ?PayloadRegion
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getShort(): ?string
    {
        return $this->short;
    }

    /**
     * @param string $short
     * @return PayloadRegion
     */
    public function setShort(?string $short): ?PayloadRegion
    {
        $this->short = $short;
        return $this;
    }

    /**
     * @return string
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return PayloadRegion
     */
    public function setCode(?string $code): ?PayloadRegion
    {
        $this->code = $code;
        return $this;
    }
}
