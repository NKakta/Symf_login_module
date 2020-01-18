<?php
declare(strict_types=1);

namespace App\CoinRemitter;

use App\Entity\Order;
use CoinRemitter\CoinRemitter;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CoinRemitterUtil
{
    /**
     * @var CoinRemitter
     */
    private $coinRemitter;

    /**
     * @var string
     */
    private $coin;

    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var string
     */
    private $password;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    public function __construct(
        UrlGeneratorInterface $urlGenerator,
        string $coin,
        string $apiKey,
        string $password
    ) {
        $this->coinRemitter = new CoinRemitter(
            [
                'coin' => $coin,
                'api_key' => $apiKey,
                'password' => $password
            ]
        );
        $this->coin = $coin;
        $this->apiKey = $apiKey;
        $this->password = $password;
        $this->urlGenerator = $urlGenerator;
    }

    public function withdraw(string $toAddress, float $amount): array
    {
        return $this->coinRemitter->withdraw(
            [
                'to_address' => $toAddress,
                'amount' => $amount
            ]
        );
    }

    public function getBalance(): float
    {
        return $this->coinRemitter->get_balance()['data']['balance'];
    }

    public function validateWalletAddress(string $address): bool
    {
        return $this->coinRemitter->withdraw(['address' => $address])['data']['valid'];
    }

    public function getTransaction(string $id): array
    {
        return $this->coinRemitter->get_transaction(['id' => $id])['data'];
    }

    public function getCryptoTotalPrice(float $price): ?float
    {
        $rate = $this->getCoinRate();
        $totalPrice = round($price / $rate, 6);
        return $totalPrice;
    }

    public function getCoinRate(): ?float
    {
        return $this->coinRemitter->get_coin_rate()['data'][$this->coin]['price'];
    }

    public function createInvoice(float $price, Order $order): array
    {
        $param = [
            'amount' => $price,      //required.
            'notify_url' => $this->getNotificationUrl(), //required,url on which you wants to receive notification,
            'name' => '',//optional,
            'currency' => 'usd',//optional,
            'expire_time' => '',//optional,
            'description' => '',//optional.
            'suceess_url' => $this->getReturnUrl($order),
            'fail_url' => $this->getCancelUrl($order),
        ];

        $invoice = $this->coinRemitter->create_invoice($param);
        return $invoice;
    }

    public function getInvoice(string $id): array
    {
        return $this->coinRemitter->get_invoice(['invoice_id' => $id]);
    }

    /**
     * @param Order $order
     * @return string
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

    /**
     * @return string
     */
    public function getNotificationUrl(): string
    {
        dd( $this->urlGenerator->generate(
            'crypto_payment_notification',
            [],
            UrlGeneratorInterface::ABSOLUTE_URL
        ));
    }
}
