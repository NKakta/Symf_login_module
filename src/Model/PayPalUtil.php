<?php
declare(strict_types=1);

namespace App\Model;

use App\Entity\Order;
use Omnipay\Common\GatewayInterface;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Omnipay;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PayPalUtil
{
    private $urlGenerator;

    private $sandboxMode;

    private $username;

    private $password;

    private $signature;

    public function __construct(
        UrlGeneratorInterface $urlGenerator,
        bool $sandboxMode,
        string $username,
        string $password,
        string $signature
    ) {
        $this->urlGenerator = $urlGenerator;
        $this->sandboxMode = $sandboxMode;
        $this->username = $username;
        $this->password = $password;
        $this->signature = $signature;
    }

    public function gateway(): GatewayInterface
    {
        /* @var $gateway GatewayInterface */
        $gateway = Omnipay::create('PayPal_Express');

        $gateway->setUsername($this->username);
        $gateway->setPassword($this->password);
        $gateway->setSignature($this->signature);
        $gateway->setTestMode($this->sandboxMode);

        return $gateway;
    }

    public function purchase(array $parameters): ResponseInterface
    {
        $response = $this->gateway()
            ->purchase($parameters)
            ->send();

        return $response;
    }

    public function complete(array $parameters): ResponseInterface
    {
        $response = $this->gateway()
            ->completePurchase($parameters)
            ->send();

        return $response;
    }

    public function formatAmount(float $amount): string
    {
        return number_format($amount, 2, '.', '');
    }

    public function getCancelUrl(Order $order): string
    {
        return $this->urlGenerator->generate(
            'product_client_index',
            [],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
    }

    public function getReturnUrl(Order $order): string
    {
        return $this->urlGenerator->generate(
            'paypal_checkout_complete',
            ['order' => $order->getId()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
    }
}
