<?php

namespace App\Event;


use App\Entity\User;
use Symfony\Component\EventDispatcher\Event;

class UserRegisteredEvent extends Event
{

    private $user;

    const NAME = "user.registered";

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUser() {
        return $this->user;
    }

}