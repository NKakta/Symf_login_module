<?php
declare(strict_types=1);

namespace App\CoinRemitter;

use CoinRemitter\CoinRemitter;

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

    public function __construct(
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

    public function createInvoice(float $price): array
    {
        $param = [
            'amount' => $price,      //required.
            'notify_url' => '', //required,url on which you wants to receive notification,
            'name' => '',//optional,
            'currency' => 'usd',//optional,
            'expire_time' => '',//optional,
            'description' => '',//optional.
            'suceess_url' => 'https://symf-login-module.test',
            'fail_url' => 'https://symf-login-module.test',
        ];

        $invoice = $this->coinRemitter->create_invoice($param);
        return $invoice;
    }

    public function getInvoice(string $id): array
    {
        return $this->coinRemitter->get_invoice(['invoice_id' => $id]);
    }
}
