<?php
declare(strict_types=1);

namespace App\Controller\Payment;

use App\CoinRemitter\CoinRemitterUtil;
use App\Entity\Order;
use App\Event\PaymentCompletedEvent;
use App\Form\Payment\PaymentFormType;
use App\Model\PaymentModel;
use App\Model\PayPalModel;
use App\PayPal\PayPalIPN;
use App\Repository\OrderRepository;
use App\UseCase\Account\CheckAvailableAccountsUseCase;
use App\UseCase\Account\PickSoldAccountsUseCase;
use App\UseCase\Account\ProcessSoldAccountsUseCase;
use App\UseCase\Order\CreateOrderUseCase;
use App\UseCase\Payment\PurchasePaypalUseCase;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PayPalController extends Controller
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
     * @var CreateOrderUseCase
     */
    private $createOrderUseCase;

    /**
     * @var PurchasePaypalUseCase
     */
    private $purchaseUseCase;

    /**
     * @var CheckAvailableAccountsUseCase
     */
    private $availableAccountsUseCase;

    /**
     * @var PickSoldAccountsUseCase
     */
    private $pickSoldAccountsUseCase;

    /**
     * @var CoinRemitterUtil
     */
    private $coinRemitterUtil;

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
        CheckAvailableAccountsUseCase $availableAccountsUseCase,
        PickSoldAccountsUseCase $pickSoldAccountsUseCase,
        CreateOrderUseCase $createOrderUseCase,
        PayPalModel $payPal,
        PurchasePaypalUseCase $purchaseUseCase,
        CoinRemitterUtil $coinRemitterUtil,
        EventDispatcherInterface $dispatcher,
        bool $paypalSandboxMode,
        LoggerInterface $logger,
        ProcessSoldAccountsUseCase $processSoldAccountsUseCase
    ) {
        $this->orderRepo = $orderRepo;
        $this->payPal = $payPal;
        $this->createOrderUseCase = $createOrderUseCase;
        $this->purchaseUseCase = $purchaseUseCase;
        $this->availableAccountsUseCase = $availableAccountsUseCase;
        $this->pickSoldAccountsUseCase = $pickSoldAccountsUseCase;
        $this->coinRemitterUtil = $coinRemitterUtil;
        $this->dispatcher = $dispatcher;
        $this->paypalSandboxMode = $paypalSandboxMode;
        $this->logger = $logger;
        $this->processSoldAccountsUseCase = $processSoldAccountsUseCase;
    }

    /**
     * @Route("/checkout/payment/paypal", name="checkout_payment_paypal")
     * @Method({"POST"})
     * @param Request $request
     * @return string|\Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     */
    public function checkout(Request $request)
    {
        $paymentModel = new PaymentModel();
        $form = $this->createForm(PaymentFormType::class, $paymentModel, ['method' => 'POST']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', 'Order failed');
            return $this->redirectToRoute('account_index');
        }

        if($this->availableAccountsUseCase->check($paymentModel))
        {
            $this->addFlash('danger', 'Not enough stock');
            return $this->redirectToRoute('account_index');
        }

        $order = $this->createOrderUseCase->create($paymentModel);

        if ($order->getMethod() == Order::TYPE_PAYMENT_CRYPTO) {
            $totalPrice = $this->coinRemitterUtil->getCryptoTotalPrice($paymentModel->getTotalPrice());
            $invoice = $this->coinRemitterUtil->createInvoice($totalPrice);

            //TODO:save invoice info in database
            if ($invoice['flag'] == 1) {
                return $this->redirect($invoice['data']['url']);
            }
        }

        if ($order->getMethod() == Order::TYPE_PAYMENT_PAYPAL) {
            $response = $this->purchaseUseCase->purchase($order);

            if ($response->isRedirect()) {
                $response->redirect();
            }
        }

        $this->addFlash('danger', 'Order unsuccessful');
        return $this->redirect($this->payPal->getCancelUrl($order));

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

        $response = $this->purchaseUseCase->purchase($order);
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

            $this->addFlash('thank_you', 'Order successful!');
            return $this->redirectToRoute('account_index');
        }

        $this->addFlash('danger', 'Order unsuccessful!');
        return $this->redirect($this->payPal->getCancelUrl($order));
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


