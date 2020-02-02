<?php
declare(strict_types=1);

namespace App\UseCase\Payment;

use App\Entity\Order;
use App\Event\PaymentCompletedEvent;
use App\Repository\OrderRepository;
use App\UseCase\Account\PickSoldAccountsUseCase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class CompleteCoinremitterUseCase
{
    /**
     * @var OrderRepository
     */
    private $repo;

    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var PickSoldAccountsUseCase
     */
    private $pickSoldAccountsUseCase;

    public function __construct(
        OrderRepository $repo,
        EntityManagerInterface $manager,
        EventDispatcherInterface $dispatcher,
        PickSoldAccountsUseCase $pickSoldAccountsUseCase
    ) {
        $this->repo = $repo;
        $this->manager = $manager;
        $this->dispatcher = $dispatcher;
        $this->pickSoldAccountsUseCase = $pickSoldAccountsUseCase;
    }

    /**
     * Creates a purchase request and sends it
     * @param string $transactionId
     */
    public function complete(string $transactionId)
    {
        /* @var $order Order */
        $order = $this->repo->findOneBy(['transactionId' => $transactionId]);

        $order->setSold(true);
        $order->setPaymentStatus(Order::PAYMENT_COMPLETED);

        $this->manager->persist($order);
        $this->manager->flush();

        $accounts = $this->pickSoldAccountsUseCase->pick($order);

        $this->dispatcher->dispatch(
            PaymentCompletedEvent::NAME,
            new PaymentCompletedEvent($order->getPayerEmail(), $accounts)
        );
    }

}
