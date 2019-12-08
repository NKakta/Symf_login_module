<?php
declare(strict_types=1);

namespace App\EventSubscriber\Security;

use App\Entity\User;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;

class AddRoleOnRegisterSubscriber implements EventSubscriberInterface
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
            FOSUserEvents::REGISTRATION_INITIALIZE => 'addRole',
        ];
    }

    /**
     * @param FormEvent $event
     */
    public function addRole(FormEvent $event)
    {
        $event->getForm()->getData();
        $user = $event->getUser();
        $user->addRole(User::ROLE_USER);
    }
}
