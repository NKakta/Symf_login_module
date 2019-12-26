<?php
declare(strict_types=1);

namespace App\Event;


use Symfony\Component\EventDispatcher\Event;

class PaymentCompletedEvent extends Event
{
    const NAME = "payment.completed";

    /**
     * @var string
     */
    private $email;

    /**
     * @var array
     */
    private $accounts;

    public function __construct(string $email, array $accounts)
    {
        $this->email = $email;
        $this->accounts = $accounts;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return PaymentCompletedEvent
     */
    public function setEmail(string $email): PaymentCompletedEvent
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return array
     */
    public function getAccounts(): array
    {
        return $this->accounts;
    }

    /**
     * @param array $accounts
     * @return PaymentCompletedEvent
     */
    public function setAccounts(array $accounts): PaymentCompletedEvent
    {
        $this->accounts = $accounts;
        return $this;
    }
}
