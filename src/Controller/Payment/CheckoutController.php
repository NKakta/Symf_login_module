<?php
declare(strict_types=1);

namespace App\Controller\Payment;

use App\CoinRemitter\CoinRemitterUtil;
use App\Entity\Order;
use App\Form\Payment\PaymentFormType;
use App\Model\PaymentModel;
use App\Model\PayPalModel;
use App\UseCase\Account\CheckAvailableAccountsUseCase;
use App\UseCase\Account\PickSoldAccountsUseCase;
use App\UseCase\Order\CreateOrderUseCase;
use App\UseCase\Payment\PurchasePaypalUseCase;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;

class CheckoutController extends AbstractController
{
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
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        CheckAvailableAccountsUseCase $availableAccountsUseCase,
        PickSoldAccountsUseCase $pickSoldAccountsUseCase,
        CreateOrderUseCase $createOrderUseCase,
        PayPalModel $payPal,
        PurchasePaypalUseCase $purchaseUseCase,
        CoinRemitterUtil $coinRemitterUtil,
        EventDispatcherInterface $dispatcher,
        LoggerInterface $logger
    ) {
        $this->payPal = $payPal;
        $this->createOrderUseCase = $createOrderUseCase;
        $this->purchaseUseCase = $purchaseUseCase;
        $this->availableAccountsUseCase = $availableAccountsUseCase;
        $this->pickSoldAccountsUseCase = $pickSoldAccountsUseCase;
        $this->coinRemitterUtil = $coinRemitterUtil;
        $this->dispatcher = $dispatcher;
        $this->logger = $logger;
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
            $invoice = $this->coinRemitterUtil->createInvoice($totalPrice, $order);

            //TODO:save invoice info in database
            if ($invoice['flag'] == 1) {
                return $this->redirect($invoice['data']['url']);
            }
            dd($invoice);
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
}


