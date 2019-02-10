<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        // create 10 users! Bam!
        // Example email: user-0@mail.com password: user-0
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setUsername('user-'.$i);
            $user->setEmail('user-'.$i.'@mail.com');
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                'user-'.$i
            ));
            $manager->persist($user);
        }
    }
}