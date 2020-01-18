<?php
declare(strict_types=1);

namespace App\Controller\Payment\Paypal;

use App\Entity\Order;
use App\Model\PayPalModel;
use App\Repository\OrderRepository;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CancelPaymentController extends AbstractController
{
    /**
     * @var OrderRepository
     */
    private $orderRepo;

    /**
     * @var PayPalModel
     */
    private $payPal;

    /**
     * @var LoggerInterface
     */
    private $logger;


    public function __construct(
        OrderRepository $orderRepo,
        PayPalModel $payPal,
        LoggerInterface $logger
    ) {
        $this->orderRepo = $orderRepo;
        $this->payPal = $payPal;
        $this->logger = $logger;
    }

    /**
     * @Route("/paypal/checkout/{order}/cancelled", name="paypal_checkout_cancelled")
     * @Method({"GET"})
     * @param $order
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function cancelled($order)
    {
        $entityManager = $this->getDoctrine()->getManager();

        /* @var $order Order */
        $order = $this->orderRepo->findOneBy(['id' => $order]);
        $order->setPaymentStatus(Order::PAYMENT_CANCELED);

        $entityManager->persist($order);
        $entityManager->flush();

        $this->addFlash('warning', 'Order cancelled!');

        return $this->redirect($this->payPal->getCancelUrl($order));
    }
}


