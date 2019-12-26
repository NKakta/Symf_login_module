<?php
declare(strict_types=1);

namespace App\Model;

class AccountImportFormModel
{
    /**
     * @var string|null
     */
    private $fileName;

    /**
     * @return string|null
     */
    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    /**
     * @param string|null $fileName
     * @return AccountImportFormModel
     */
    public function setFileName(?string $fileName): AccountImportFormModel
    {
        $this->fileName = $fileName;
        return $this;
    }
}
