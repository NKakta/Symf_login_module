<?php
declare(strict_types=1);

namespace App\EventSubscriber\Security;

use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;

class RedirectAfterRegistrationSubscriber implements EventSubscriberInterface
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            FOSUserEvents::REGISTRATION_SUCCESS => 'onRegistrationSuccess',
            FOSUserEvents::REGISTRATION_CONFIRMED => 'onRegistrationSuccess',
        ];
    }

    /**
     * @param FormEvent $event
     */
    public function onRegistrationSuccess(FormEvent $event)
    {
        $url = $this->router->generate('account_index');
        $response = new RedirectResponse($url);
        $event->setResponse($response);
    }
}
