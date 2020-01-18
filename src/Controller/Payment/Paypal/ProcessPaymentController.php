<?php
declare(strict_types=1);

namespace App\Controller\Payment\Paypal;

use App\Entity\Order;
use App\Event\PaymentCompletedEvent;
use App\PayPal\PayPalIPN;
use App\Repository\OrderRepository;
use App\UseCase\Account\ProcessSoldAccountsUseCase;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProcessPaymentController extends AbstractController
{
    /**
     * @var OrderRepository
     */
    private $orderRepo;

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var bool
     */
    private $paypalSandboxMode;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var ProcessSoldAccountsUseCase
     */
    private $processSoldAccountsUseCase;


    public function __construct(
        OrderRepository $orderRepo,
        LoggerInterface $logger,
        ProcessSoldAccountsUseCase $processSoldAccountsUseCase,
        bool $paypalSandboxMode
    ) {
        $this->orderRepo = $orderRepo;
        $this->logger = $logger;
        $this->processSoldAccountsUseCase = $processSoldAccountsUseCase;
        $this->paypalSandboxMode = $paypalSandboxMode;
    }

    /**
     * This is the PayPal IPN url
     * @Route("/paypal/ipn", name="paypal_instant_notification")
     * @Method({"POST"})
     * @param Request $request
     * @return Response
     */
    public function processPaymentNotification(Request $request)
    {
        $params = $request->request->all();
        $params = [
            'payment_status' => 'Completed',
            'txn_id' => '616463550'
        ];

        if ($params['txn_type'] != 'express_checkout') {
            $this->logger->log('info', 'txn_type is not express_checkout set to '.$params['txn_type']);
            return new Response('', Response::HTTP_OK);
        }

        if (strtolower($params['payment_status']) != 'completed') {
            $this->logger->log('info', 'wrong payment_status set to '.$params['payment_status']);
            return new Response('', Response::HTTP_OK);
        }

        $ipn = new PaypalIPN();
        if($this->paypalSandboxMode) {
            $ipn->useSandbox();
        }

        $verified = $ipn->verifyIPN();

        if ($verified) {
            /* @var $order Order */
            $order = $this->orderRepo->findOneBy(['id' => $params['txn_id']]);

            $accounts = $this->processSoldAccountsUseCase->process($order);

            $this->dispatcher->dispatch(
                PaymentCompletedEvent::NAME,
                new PaymentCompletedEvent($order->getPayerEmail(), $accounts)
            );
        }

        $this->logger->log('info', 'paypal ipn completed');
        return new Response('', Response::HTTP_OK);
    }
}


