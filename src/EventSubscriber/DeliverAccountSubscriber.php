<?php
declare(strict_types=1);

namespace App\EventSubscriber;

use App\Entity\User;
use App\Event\PaymentCompletedEvent;
use App\UseCase\Account\DeliverAccountUseCase;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;

class DeliverAccountSubscriber implements EventSubscriberInterface
{
    /**
     * @var DeliverAccountUseCase
     */
    private $deliverAccountUseCase;

    /**
     * @param DeliverAccountUseCase $deliverAccountUseCase
     */
    public function __construct(DeliverAccountUseCase $deliverAccountUseCase)
    {
        $this->deliverAccountUseCase = $deliverAccountUseCase;
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            PaymentCompletedEvent::NAME => 'deliverAccounts',
        ];
    }

    /**
     * @param PaymentCompletedEvent $event
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function deliverAccounts(PaymentCompletedEvent $event)
    {
        $accounts = $event->getAccounts();
        $email = $event->getEmail();

        $this->deliverAccountUseCase->deliver($email, $accounts);
    }
}
