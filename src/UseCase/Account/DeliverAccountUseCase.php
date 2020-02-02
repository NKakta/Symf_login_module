<?php
declare(strict_types=1);

namespace App\UseCase\Account;

use App\Entity\Account;
use App\Model\PaymentModel;
use App\Repository\AccountRepository;

class DeliverAccountUseCase
{
    /**
     * @var AccountRepository
     */
    private $accountRepo;

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var \Twig\Environment
     */
    private $twig;

    /**
     * @var string
     */
    private $fromEmail;

    public function __construct(
        AccountRepository $accountRepo,
        \Swift_Mailer $mailer,
        \Twig\Environment $twig,
        string $fromEmail
    ) {
        $this->accountRepo = $accountRepo;
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->fromEmail = $fromEmail;
    }

    /**
     * @param string $toEmail
     * @param Account[] $accounts
     * @return void
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function deliver(string $toEmail, array $accounts)
    {
        $body = $this->twig->render(
            'emails/deliver_account.html.twig',
            [
                'accounts' => $accounts,
                'publicUrl' => 'https://example.com',
            ]
        );

        /** @var \Swift_Message $message */
        $message = $this->mailer->createMessage();
        $message
            ->setFrom($this->fromEmail)
            ->setSubject('Account delivery')
            ->setTo($toEmail)
            ->setBody($body, 'text/html');

        $this->mailer->send($message);
    }

}
