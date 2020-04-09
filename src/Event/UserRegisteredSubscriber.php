<?php
declare(strict_types=1);

namespace App\Event;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UserRegisteredSubscriber implements EventSubscriberInterface
{
    private $mailer;
    private $urlGenerator;

    public function __construct(\Swift_Mailer $mailer, UrlGeneratorInterface $urlGenerator)
    {
        $this->mailer = $mailer;
        $this->urlGenerator = $urlGenerator;
    }

    public static function getSubscribedEvents()
    {
        return [
            UserRegisteredEvent::NAME => 'onUserRegistered'
        ];
    }

    public function onUserRegistered(UserRegisteredEvent $event)
    {
        $loginUrl = $this->urlGenerator->generate('app_login', [], UrlGeneratorInterface::ABSOLUTE_URL);

        $message = (new \Swift_Message('Welcome to user module '. $event->getUser()->getUsername()))
            ->setFrom('no-reply@loginModule.com')
            ->setTo($event->getUser()->getEmail())
            ->setBody('You have registered successfully! You can log in here: ' . $loginUrl);

        return $this->mailer->send($message);
    }
}
