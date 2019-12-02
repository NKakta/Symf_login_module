<?php
declare(strict_types=1);

namespace App\Controller\Payment;

use App\Entity\Order;
use App\Form\Payment\PaymentFormType;
use App\Model\PaymentModel;
use App\Model\PayPalModel;
use App\Repository\AccountRepository;
use App\Repository\OrderRepository;
use App\UseCase\Order\CreateOrderUseCase;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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
     * @var AccountRepository
     */
    private $accountRepo;

    /**
     * @var CreateOrderUseCase
     */
    private $createOrderUseCase;


    public function __construct(
        OrderRepository $orderRepo,
        AccountRepository $accountRepo,
        CreateOrderUseCase $createOrderUseCase,
        PayPalModel $payPal
    ) {
        $this->orderRepo = $orderRepo;
        $this->payPal = $payPal;
        $this->accountRepo = $accountRepo;
        $this->createOrderUseCase = $createOrderUseCase;
    }

    /**
     * @Route("/checkout/payment/paypal", name="checkout_payment_paypal")
     * @Method({"POST"})
     * @Template("account/index.html.twig")
     * @param Request $request
     * @return string|\Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     */
    public function checkout(Request $request)
    {
        $paymentModel = new PaymentModel();
        $form = $this->createForm(PaymentFormType::class, $paymentModel, ['method' => 'POST']);
        $form->handleRequest($request);

        if (!($form->isSubmitted() && $form->isValid())) {
            $this->addFlash('danger', 'Bad input');
            return $this->redirectToRoute('account_index');
        }

        $availableAccounts = count($this->accountRepo->findBy(['product' => $paymentModel->getProductId(), 'sold' => false]));

        if ($availableAccounts < $paymentModel->getQuantity()) {
            $this->addFlash('danger', 'Not enough items in stock');
            return $this->redirectToRoute('account_index');
        }

        $order = $this->createOrderUseCase->create($paymentModel);

        $response = $this->payPal->purchase([
            'amount' => $this->payPal->formatAmount($order->getTotalPrice()),
            'transactionId' => $order->getId(),
            'currency' => 'USD',
            'cancelUrl' => $this->payPal->getCancelUrl($order),
            'returnUrl' => $this->payPal->getReturnUrl($order),
        ]);

        if ($response->isRedirect()) {
            $response->redirect();
        }

        $this->addFlash('danger', 'Order unsuccessful');

        return $this->redirect($this->payPal->getCancelUrl($order));
    }

    /**
     * @Route("/paypal/checkout/{order}/completed", name="paypal_checkout_completed")
     * @Method({"GET"})
     * @Template("account/index.html.twig")
     * @param Request $request
     * @param $order
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function completed(Request $request, $order)
    {
        /* @var $order Order */
        $order = $this->orderRepo->findOneBy(['id' => $order]);

        $entityManager = $this->getDoctrine()->getManager();

        $response = $this->payPal->complete([
            'amount' => $this->payPal->formatAmount($order->getTotalPrice()),
            'transactionId' => $order->getId(),
            'currency' => 'USD',
            'cancelUrl' => $this->payPal->getCancelUrl($order),
            'returnUrl' => $this->payPal->getReturnUrl($order),
        ]);

        $quantity = $order->getQuantity();

        $accounts = $this->accountRepo->getAvailableAccountsByOrder($order, $quantity);

        foreach($accounts as $account) {
            $account->setSold(true);
            $account->setOrder($order);
            $entityManager->persist($account);
        }

        if ($response->isSuccessful()) {
            $order->setTransactionId($response->getTransactionReference());
            $order->setPaymentStatus(Order::PAYMENT_COMPLETED);

            $entityManager->persist($order);
            $entityManager->flush();

            $this->addFlash('thank_you', 'Order successful!');

            return $this->redirectToRoute('account_index');
        }
        $entityManager->flush();

        $this->addFlash('danger', 'Order unsuccessful!');
        return $this->redirect($this->payPal->getCancelUrl($order));
    }

    /**
     * @Route("/paypal/checkout/{order}/cancelled", name="paypal_checkout_cancelled")
     * @Method({"GET"})
     * @Template("account/index.html.twig")
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


