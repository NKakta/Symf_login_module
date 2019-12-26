<?php
declare(strict_types=1);

namespace App\UseCase\Account;

use App\Entity\Account;
use App\Entity\Order;
use App\Model\PaymentModel;
use App\Repository\AccountRepository;

class PickSoldAccountsUseCase
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
     * @param Order $order
     * @return Account[]
     */
    public function pick(Order $order): array
    {
        $accounts = $this->accountRepo->getAvailableAccountsByOrder($order, $order->getQuantity());

        foreach($accounts as $account) {
            $account->setSold(true);
            $account->setOrder($order);
        }

        return $accounts;
    }

}
