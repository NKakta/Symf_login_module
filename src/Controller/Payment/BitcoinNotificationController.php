<?php
declare(strict_types=1);

namespace App\Controller\Payment;

use App\CoinRemitter\CoinRemitterUtil;
use App\Entity\Order;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class BitcoinNotificationController extends Controller
{
    /**
     * @var CoinRemitterUtil
     */
    private $coinRemitterUtil;
    /**
     * @var Logger
     */
    private $logger;


    public function __construct(
        CoinRemitterUtil $coinRemitterUtil,
        LoggerInterface $logger
    ) {
        $this->coinRemitterUtil = $coinRemitterUtil;
        $this->logger = $logger;
    }

    /**
     * @Route("/payment/checkout/completed", name="crypto_checkout_completed")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param $order
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function completed(Request $request)
    {
        $this->logger->log('critical', 'logged critical stuff');
        dd($request->request->all());
        $response = [
            'id' => "5de4b1235aa55814b8223952",
            'invoice_id' => "BTC080",
            'url' => "https://coinremitter.com/invoice/5de48b77b846fe407d049fa2",
            'total_amount' => 0.01,
            'paid_amount' => 0.01,
            'usd_amount' => 72.8901,
            'coin' => "BTC",
            'name' => "My Test Wallet",
            'description' => "Description",
            'wallet_name' => "New Test-LTC",
            'address'=>"QbqVdiLvPGGrm4DqduNUWbj3r8fLAz8UtV",
            'payment_history'=>[
                [
                    'txid'=>'c4b853d4be7586798870a4aa766e3bb781eddb24aaafd81da8f66263017b872d',
                    'explorer_url'=>'http://btc.com/exp/c4b853d4be7586798870a4aa766e3bb781eddb24aaafd81da8f66263017b872d',
                    'amount'=>0.005,
                    'date'=>'2019-12-0212:09:02',
                    'confirmation'=>134
                ],
                [
                    'txid'=>'a2541253ab72d7cf29f2f9becb1e31320dd0ed418f761ab1973dc9e412a51c7f',
                   'explorer_url'=>'http://btc.com/exp/a2541253ab72d7cf29f2f9becb1e31320dd0ed418f761ab1973dc9e412a51c7f',
                   'amount'=>0.005,
                   'date'=>'2019-12-0212:15:02',
                   'confirmation'=>131
                ]
            ],
            'status'=>"Paid",
            'status_code'=>1,
            'suceess_url'=>"yourdomain.com/success-url",
            'fail_url'=>"yourdomain.com/fail-url",
            'notify_url'=>"yourdomain.com/notify-url",
            'expire_on'=>"2018-12-06 10:35:57",
            'invoice_date'=>"2019-12-02 12:07:23",
            'last_updated_date'=>"2019-12-02 12:15:02"
        ];

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

            $this->addFlash('thank_you', 'Order successful!');
            return $this->redirectToRoute('account_index');
        }

        $this->addFlash('danger', 'Order unsuccessful!');
        return $this->redirect($this->payPal->getCancelUrl($order));
    }
}

