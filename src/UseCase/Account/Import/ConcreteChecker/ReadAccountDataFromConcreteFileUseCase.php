<?php
declare(strict_types=1);

namespace App\UseCase\Account\Import\ConcreteChecker;

use App\Model\Import\ConcreteChecker\Account;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ReadAccountDataFromConcreteFileUseCase
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
     * @param UploadedFile $file
     * @return Account[]
     */
    public function read(UploadedFile $file): array
    {
        $data = file_get_contents($file->getPathname());
        $model = $this->serializer->deserialize(
            $data,
            'array<App\Model\Import\ConcreteChecker\Account>',
            'json'
        );
        return $model;
    }

}
