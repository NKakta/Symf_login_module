<?php
declare(strict_types=1);

namespace App\Model;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class AccountImportFormModel
{
    const TYPE_GREEN_CHECKER = 'green_checker';
    const TYPE_WHITE_CHECKER = 'white_checker';

    /**
     * @var string|null
     * @Assert\NotBlank()
     */
    private $type;

    /**
     * @var string|null
     * @Assert\NotBlank()
     */
    private $region;

    /**
     * @var UploadedFile|null
     * @Assert\NotBlank()
     */
    private $file;

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     * @return AccountImportFormModel
     */
    public function setType(?string $type): AccountImportFormModel
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return UploadedFile|null
     */
    public function getFile(): ?UploadedFile
    {
        return $this->file;
    }

    /**
     * @param UploadedFile $file
     * @return AccountImportFormModel
     */
    public function setFile(UploadedFile $file): AccountImportFormModel
    {
        $this->file = $file;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRegion(): ?string
    {
        return $this->region;
    }

    /**
     * @param string|null $region
     * @return AccountImportFormModel
     */
    public function setRegion(?string $region): AccountImportFormModel
    {
        $this->region = $region;
        return $this;
    }
}
