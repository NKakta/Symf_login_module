<?php
declare(strict_types=1);

namespace App\UseCase\Account;

use App\Entity\Account;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;

class ProcessSoldAccountsUseCase
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var PickSoldAccountsUseCase
     */
    private $pickSoldAccountsUseCase;

    public function __construct(
        EntityManagerInterface $em,
        PickSoldAccountsUseCase $pickSoldAccountsUseCase
    ) {
        $this->pickSoldAccountsUseCase = $pickSoldAccountsUseCase;
        $this->em = $em;
    }

    /**
     * @param Order $order
     * @return Account[]
     */
    public function process(Order $order): array
    {
        $order->setSold(true);
        $order->setPaymentStatus(Order::PAYMENT_COMPLETED);
        $this->em->persist($order);

        $accounts = $this->pickSoldAccountsUseCase->pick($order);

        foreach ($accounts as $account) {
            $account->setSold(true);
            $account->setOrder($order);
            $this->em->persist($account);
        }
        $this->em->flush();

        return $accounts;
    }

}
