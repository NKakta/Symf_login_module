<?php
declare(strict_types=1);

namespace App\Controller\Payment\Paypal;

use App\Entity\Order;
use App\Event\PaymentCompletedEvent;
use App\Model\PayPalModel;
use App\Repository\OrderRepository;
use App\UseCase\Account\PickSoldAccountsUseCase;
use App\UseCase\Payment\CompletePaypalUseCase;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;

class CompletedPaymentController extends AbstractController
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

    /**
     * @var CompletePaypalUseCase
     */
    private $completeUseCase;

    /**
     * @var PickSoldAccountsUseCase
     */
    private $pickSoldAccountsUseCase;

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;


    public function __construct(
        OrderRepository $orderRepo,
        PayPalModel $payPal,
        LoggerInterface $logger,
        CompletePaypalUseCase $completeUseCase,
        PickSoldAccountsUseCase $pickSoldAccountsUseCase,
        EventDispatcherInterface $dispatcher
    ) {
        $this->orderRepo = $orderRepo;
        $this->payPal = $payPal;
        $this->logger = $logger;
        $this->completeUseCase = $completeUseCase;
        $this->pickSoldAccountsUseCase = $pickSoldAccountsUseCase;
        $this->dispatcher = $dispatcher;
    }


    /**
     * @Route("/paypal/checkout/{order}/completed", name="paypal_checkout_completed")
     * @Method({"GET"})
     * @param Request $request
     * @param $order
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function completed(Request $request, $order)
    {
        /* @var $order Order */
        $order = $this->orderRepo->findOneBy(['id' => $order]);

        $entityManager = $this->getDoctrine()->getManager();

        $response = $this->completeUseCase->complete($order);
        $accounts = $this->pickSoldAccountsUseCase->pick($order);

        foreach($accounts as $account) {
            $entityManager->persist($account);
        }

        if ($response->isSuccessful()) {
            $order->setTransactionId($response->getTransactionReference());
            $order->setPaymentStatus(Order::PAYMENT_COMPLETED);
            $entityManager->persist($order);
            $entityManager->flush();

            $this->dispatcher->dispatch(
                PaymentCompletedEvent::NAME,
                new PaymentCompletedEvent($order->getPayerEmail(), $accounts)
            );

            $this->addFlash('thank_you', 'Payment is sucessful with reference code ' . $response->getTransactionReference());
            return $this->redirectToRoute('account_index');
        }

        $this->addFlash('danger', 'Order unsuccessful!');
        return $this->redirect($this->payPal->getCancelUrl($order));
    }
}


