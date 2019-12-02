<?php
declare(strict_types=1);

namespace App\UseCase\Order;

use App\Entity\Order;
use App\Model\PaymentModel;
use Doctrine\ORM\EntityManagerInterface;

class CreateOrderUseCase
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    public function __construct(
        EntityManagerInterface $manager
    ) {
        $this->manager = $manager;
    }

    /**
     * @param PaymentModel $paymentModel
     * @return string
     * @throws \Exception
     */
    public function create(PaymentModel $paymentModel): Order
    {
        $order = new Order();
        $order->setTransactionId(null);
        $order->setQuantity($paymentModel->getQuantity());
        $order->setPaymentStatus(Order::PAYMENT_PENDING);
        $order->setSold(false);
        $order->setProduct($paymentModel->getProductId());
        $order->setPrice((string)($paymentModel->getQuantity() * $paymentModel->getProductId()->getPrice()));
        $order->setMethod($paymentModel->getPaymentMethod());
        $order->setCreatedAt(new \DateTime());
        $order->setUpdatedAt(new \DateTime());

        $this->manager->persist($order);
        $this->manager->flush();

        return $order;
    }

}
