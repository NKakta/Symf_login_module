<?php

namespace App\Controller;


use App\Entity\Tracking;
use App\Entity\User;
use App\Event\UserRegisteredEvent;
use App\Form\LoginFormType;
use App\Form\RegistrationFormType;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SecurityController extends AbstractController
{
    private $validator;

    public function __construct(ValidatorInterface $validator) {
        $this->validator = $validator;
    }

    /**
     * @Route("/login", name="app_login")
     * @Method({"GET", "POST"})
     * @Template("security/login.html.twig")
     */
    public function loginAction(AuthenticationUtils $authenticationUtils)
    {
        if ($this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('app_homepage');
        }

        $emailConstraint = new Assert\Email();
        $emailConstraint->message = 'Neteisingas e-mail adredas';

        $notBlankConstraint = new Assert\NotBlank();
        $notBlankConstraint->message = 'e-mail laukelis, neglai būti tuščias';

        $form = $this->createForm(LoginFormType::class);

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return [
            'loginForm' => $form->createView(),
            'last_username' => $lastUsername,
            'error' => $error,
        ];
    }

    /**
     * @Route("/logout", name="app_logout")
     * @throws \RuntimeException
     */
    public function logoutAction()
    {
        throw new \RuntimeException('This should never be called directly.');
    }

    /**
     * @Route("/register", name="app_register")
     * @Method({"GET", "POST"})
     * @Template("registration/register.html.twig")
     */
    public function registerAction(Request $request, UserPasswordEncoderInterface $passwordEncoder, EventDispatcherInterface $dispatcher)
    {
        if ($this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('app_homepage');
        }



        $user = new User();
        $user->setRole('ROLE_USER');

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );


            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->getConnection()->beginTransaction();
            $user->setCredits(50);
            $entityManager->persist($user);
            $entityManager->flush();

            $event = new UserRegisteredEvent($user);
            $dispatcher->dispatch(UserRegisteredEvent::NAME, $event);

            $entityManager->commit();
            $this->addFlash('success', 'Sveikiname užsiregistravus!');

            return $this->redirectToRoute('app_homepage');
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', 'Oooops kažkas nepavyko...');
        }

        return ['registrationForm' => $form->createView()];
    }
}