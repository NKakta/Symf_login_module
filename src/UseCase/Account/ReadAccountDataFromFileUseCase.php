<?php
declare(strict_types=1);

namespace App\UseCase\Account;

use App\Model\AccountImport;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ReadAccountDataFromFileUseCase
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @return AccountImport
     */
    public function read(UploadedFile $file): AccountImport
    {
        $data = file_get_contents($file->getPathname());
        $model = $this->serializer->deserialize(
            $data,
            AccountImport::class,
            'json'
        );

        return $model;
    }

}
