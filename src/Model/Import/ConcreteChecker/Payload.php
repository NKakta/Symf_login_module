<?php
declare(strict_types=1);

namespace App\Model\Import\ConcreteChecker;

use JMS\Serializer\Annotation as Serializer;

class Payload
{
    /**
     * @var string
     * @Serializer\Type("string")
     * @Serializer\SerializedName("Username")
     */
    protected $username;

    /**
     * @var string
     * @Serializer\Type("string")
     * @Serializer\SerializedName("Password")
     */
    protected $password;

    /**
     * @var string
     * @Serializer\Type("App\Model\Import\ConcreteChecker\PayloadRegion")
     * @Serializer\SerializedName("Region")
     */
    protected $region;

    /**
     * @return string
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return Payload
     */
    public function setUsername(string $username): Payload
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return Payload
     */
    public function setPassword(string $password): Payload
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string
     */
    public function getRegion(): ?string
    {
        return $this->region;
    }

    /**
     * @param string $region
     * @return Payload
     */
    public function setRegion(string $region): Payload
    {
        $this->region = $region;
        return $this;
    }


}
