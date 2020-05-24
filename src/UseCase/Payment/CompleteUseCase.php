<?php
declare(strict_types=1);

namespace App\UseCase\Payment;

use App\Entity\Order;
use App\Model\PayPalUtil;
use Omnipay\Common\Message\ResponseInterface;

class CompleteUseCase
{
    private $payPal;

    public function __construct(
        PayPalUtil $payPal
    ) {
        $this->payPal = $payPal;
    }

    /**
     * Creates a purchase request and sends it
     */
    public function complete(Order $order): ResponseInterface
    {
        $response = $this->payPal->complete([
            'amount' => $this->payPal->formatAmount((float)$order->getTotalPrice()),
            'transactionId' => $order->getId(),
            'currency' => 'USD',
            'cancelUrl' => $this->payPal->getCancelUrl($order),
            'returnUrl' => $this->payPal->getReturnUrl($order),
        ]);

        return $response;
    }

}
