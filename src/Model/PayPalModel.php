<?php
declare(strict_types=1);

namespace App\Model;

use App\Entity\Order;
use Omnipay\Common\GatewayInterface;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Omnipay;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PayPalModel
{
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var bool
     */
    private $sandboxMode;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
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

    /**
     * @return mixed
     */
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

    /**
     * @param array $parameters
     * @return mixed
     */
    public function purchase(array $parameters): ResponseInterface
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
    public function complete(array $parameters): ResponseInterface
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
    public function formatAmount($amount): string
    {
        return number_format($amount, 2, '.', '');
    }

    /**
     * @return
     */
    public function getCancelUrl(Order $order): string
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
    public function getReturnUrl(Order $order): string
    {
        return $this->urlGenerator->generate(
            'paypal_checkout_completed',
            [ 'order' => $order->getId()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
    }
}
