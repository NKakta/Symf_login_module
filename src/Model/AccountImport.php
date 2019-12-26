<?php
declare(strict_types=1);

namespace App\Model;

use JMS\Serializer\Annotation as Serializer;

class AccountImport
{
    /**
     * @var string
     * @Serializer\Type("string")
     * @Serializer\SerializedName("Version")
     */
    private $version;

    /**
     * @var array
     * @Serializer\Type("array<App\Model\ImportedAccountModel>")
     * @Serializer\SerializedName("Accounts")
     */
    private $accounts;

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @param string $version
     * @return AccountImport
     */
    public function setVersion(string $version): AccountImport
    {
        $this->version = $version;
        return $this;
    }

    /**
     * @return ImportedAccountModel[]
     */
    public function getAccounts(): array
    {
        return $this->accounts;
    }

    /**
     * @param array $accounts
     * @return AccountImport
     */
    public function setAccounts(array $accounts): AccountImport
    {
        $this->accounts = $accounts;
        return $this;
    }
}
