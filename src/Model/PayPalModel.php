<?php
declare(strict_types=1);

namespace App\Model;

use App\Entity\Order;
use Omnipay\Common\GatewayInterface;
use Omnipay\Omnipay;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PayPalModel
{
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    public function __construct(
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @return mixed
     */
    public function gateway()
    {
        /* @var $gateway GatewayInterface */
        $gateway = Omnipay::create('PayPal_Express');

        $gateway->setUsername('sb-kjo5z622450_api1.business.example.com');
        $gateway->setPassword('CKBYNN5P3GEYUD2N');
        $gateway->setSignature('A.-UecNL5hJyWLfqBV-1IjekrtvdAhAcRfOZALi09IYyyRBffoBSlvyj');
        $gateway->setTestMode(true);

        return $gateway;
    }

    /**
     * @param array $parameters
     * @return mixed
     */
    public function purchase(array $parameters)
    {
        $response = $this->gateway()
            ->purchase($parameters)
            ->send();

        return $response;
    }

    /**
     * @param array $parameters
     * @return mixed
     */
    public function complete(array $parameters)
    {
        $response = $this->gateway()
            ->completePurchase($parameters)
            ->send();

        return $response;
    }

    /**
     * @param $amount
     * @return string
     */
    public function formatAmount($amount)
    {
        return number_format($amount, 2, '.', '');
    }

    /**
     * @return
     */
    public function getCancelUrl(Order $order)
    {
        return $this->urlGenerator->generate(
            'paypal_checkout_cancelled',
            [ 'order' => $order->getId()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
    }

    /**
     * @param Order $order
     * @return string
     */
    public function getReturnUrl(Order $order)
    {
        return $this->urlGenerator->generate(
            'paypal_checkout_completed',
            [ 'order' => $order->getId()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
    }
}
