<?php
declare(strict_types=1);

namespace App\UseCase\Payment;

use App\Entity\Order;
use App\Model\PayPalModel;
use Omnipay\Common\Message\ResponseInterface;

class PurchasePaypalUseCase
{
    /**
     * @var PayPalModel
     */
    private $payPal;

    public function __construct(
        PayPalModel $payPal
    ) {
        $this->payPal = $payPal;
    }

    /**
     * Creates a purchase request and sends it
     * @param Order $order
     * @return ResponseInterface
     */
    public function purchase(Order $order): ResponseInterface
    {
        $response = $this->payPal->purchase([
            'amount' => $this->payPal->formatAmount($order->getTotalPrice()),
            'transactionId' => $order->getId(),
            'currency' => 'USD',
            'cancelUrl' => $this->payPal->getCancelUrl($order),
            'returnUrl' => $this->payPal->getReturnUrl($order),
        ]);

        return $response;
    }

}
