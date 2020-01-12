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
     */
    public function check(PaymentModel $paymentModel): bool
    {
        $availableAccounts = count($this->accountRepo->findBy(
            [
                'product' => $paymentModel->getProductId(),
                'sold' => false
            ]
        ));

        return $availableAccounts < $paymentModel->getQuantity();
    }

}
