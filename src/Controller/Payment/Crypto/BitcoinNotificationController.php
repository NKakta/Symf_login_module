<?php
declare(strict_types=1);

namespace App\Controller\Payment\Crypto;

use App\CoinRemitter\CoinRemitterUtil;
use App\UseCase\Payment\CompleteCoinremitterUseCase;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BitcoinNotificationController extends AbstractController
{
    /**
     * @var CoinRemitterUtil
     */
    private $coinRemitterUtil;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var CompleteCoinremitterUseCase
     */
    private $completeUseCase;


    public function __construct(
        CoinRemitterUtil $coinRemitterUtil,
        LoggerInterface $logger,
        CompleteCoinremitterUseCase $completeUseCase
    ) {
        $this->coinRemitterUtil = $coinRemitterUtil;
        $this->logger = $logger;
        $this->completeUseCase = $completeUseCase;
    }

    /**
     * @Route("/crypto/notification", name="crypto_payment_notification")
     * @Method({"POST"})
     * @param Request $request
     * @return Response
     */
    public function process(Request $request)
    {
        $this->logger->log('info', 'Reiceived notification from coinremitter:');
        $this->logger->log('info', json_encode($request->request->all()));

        $data = $request->request->all();

        if(!$data) {
            return new Response();
        }

        if(isset($data['status']) && $data['status'] == 'Paid') {
            $this->completeUseCase->complete($data['invoice_id']);
        }

        return new Response();
    }
}

//{
//    "id": "5e24507ffbf03e31ed2e2ec2",
//  "invoice_id": "TCN029",
//  "merchant_id": "5e013d74fbf03e250550952e",
//  "url": "https:\/\/coinremitter.com\/invoice\/5e24507ffbf03e31ed2e2ec2",
//  "usd_amount": "1",
//  "base_currency": "USD",
//  "coin": "TCN",
//  "name": "testcoin",
//  "description": "",
//  "wallet_name": "testcoin",
//  "address": "RM1g6ZHvMbrb3EAAQmvivoQA1BmnC2njje",
//  "status": "Paid",
//  "status_code": "1",
//  "suceess_url": "http:\/\/159.89.49.125\/paypal\/checkout\/372b5b00-3aba-11ea-a35a-2ade5f5a084f\/completed",
//  "fail_url": "http:\/\/159.89.49.125\/paypal\/checkout\/372b5b00-3aba-11ea-a35a-2ade5f5a084f\/cancelled",
//  "notify_url": "http:\/\/159.89.49.125\/crypto\/notification",
//  "expire_on": "",
//  "invoice_date": "2020-01-19 12:50:07",
//  "last_updated_date": "2020-01-19 13:14:02",
//  "total_amount": {
//    "TCN": "1",
//    "USD": "1"
//  },
//  "paid_amount": {
//    "TCN": "1",
//    "USD": "1"
//  },
//  "conversion_rate": {
//    "USD_TCN": "1",
//    "TCN_USD": "1"
//  },
//  "payment_history": [
//    {
//        "txid": "a19f94d8f9d33e7a1dfcfab4a1d9991f4641c7024d8061704ed556d4d79e4b51",
//      "explorer_url": "a19f94d8f9d33e7a1dfcfab4a1d9991f4641c7024d8061704ed556d4d79e4b51",
//      "amount": "1",
//      "date": "2020-01-19 12:53:01",
//      "confirmation": "1"
//    }
//  ]
//}


