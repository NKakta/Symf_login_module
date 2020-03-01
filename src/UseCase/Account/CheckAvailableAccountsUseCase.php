<?php
declare(strict_types=1);

namespace App\UseCase\Account;

use App\Model\PaymentModel;
use App\Repository\AccountRepository;

class CheckAvailableAccountsUseCase
{
    /**
     * @var AccountRepository
     */
    private $accountRepo;

    public function __construct(
        AccountRepository $accountRepo
    ) {
        $this->accountRepo = $accountRepo;
    }

    /**
     * @param PaymentModel $paymentModel
     * @return bool
     * returns false not enough items for purchase
     */
    public function check(PaymentModel $paymentModel): bool
    {
        $availableAccounts = count($this->accountRepo->findBy(
            [
                'product' => $paymentModel->getProductId(),
                'region' => $paymentModel->getRegion(),
                'sold' => false
            ]
        ));

        return $availableAccounts < $paymentModel->getQuantity();
    }

}
