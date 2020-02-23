<?php
declare(strict_types=1);

namespace App\UseCase\Account;

use App\Entity\Account;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;

class SetProductUseCase
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var ProductRepository
     */
    private $productRepo;

    /**
     * @var ValidateAccForProductUseCase
     */
    private $validateUseCase;

    public function __construct(
        EntityManagerInterface $em,
        ProductRepository $productRepo,
        ValidateAccForProductUseCase $validateUseCase
    ) {
        $this->em = $em;
        $this->productRepo = $productRepo;
        $this->validateUseCase = $validateUseCase;
    }

    /**
     * Select what product the imported account should belong to if not found sets null
     * @param Account $account
     */
    public function setProduct(Account $account): void
    {
        $products = $this->productRepo->getAllOrdered();

        foreach ($products as $product) {
            if ($this->validateUseCase->isValid($product, $account)) {
                $account->setProduct($product);
            }
        }
    }
}
