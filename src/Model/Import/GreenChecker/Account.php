<?php
declare(strict_types=1);

namespace App\Model\Import\GreenChecker;

use JMS\Serializer\Annotation as Serializer;

class Account
{
    /**
     * @var string
     * @Serializer\Type("string")
     * @Serializer\SerializedName("username")
     */
    protected $username;

    /**
     * @var string
     * @Serializer\Type("string")
     * @Serializer\SerializedName("password")
     */
    protected $password;

    /**
     * @var string
     * @Serializer\Type("string")
     * @Serializer\SerializedName("region")
     */
    protected $region;

    /**
     * @var string
     * @Serializer\Type("string")
     * @Serializer\SerializedName("mail")
     */
    protected $mail;

    /**
     * @return string
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return Account
     */
    public function setUsername(string $username): Account
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
     * @return Account
     */
    public function setPassword(string $password): Account
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
     * @return Account
     */
    public function setRegion(string $region): Account
    {
        $this->region = $region;
        return $this;
    }

    /**
     * @return string
     */
    public function getMail(): ?string
    {
        return $this->mail;
    }

    /**
     * @param string $mail
     * @return Account
     */
    public function setMail(string $mail): Account
    {
        $this->mail = $mail;
        return $this;
    }
}
