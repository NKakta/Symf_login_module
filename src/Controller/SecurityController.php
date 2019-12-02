<?php
declare(strict_types=1);

namespace App\Controller;


use App\Entity\User;
use App\Event\UserRegisteredEvent;
use App\Form\LoginFormType;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SecurityController extends AbstractController
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    public function __construct(
        ValidatorInterface $validator,
        UserPasswordEncoderInterface $passwordEncoder,
        EventDispatcherInterface $dispatcher
    ) {
        $this->validator = $validator;
        $this->passwordEncoder = $passwordEncoder;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @Route("/login", name="app_login")
     * @Method({"GET", "POST"})
     * @Template("security/login.html.twig")
     * @param AuthenticationUtils $authenticationUtils
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function loginAction(AuthenticationUtils $authenticationUtils)
    {
        if ($this->isGranted(User::ROLE_USER)) {
            return $this->redirectToRoute('account_index');
        }

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
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function registerAction(Request $request)
    {
        if ($this->isGranted(User::ROLE_USER)) {
            return $this->redirectToRoute('account_index');
        }

        $user = new User();
        $user->setRole(User::ROLE_USER);

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $this->passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );


            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->getConnection()->beginTransaction();

            $entityManager->persist($user);
            $entityManager->flush();

            $event = new UserRegisteredEvent($user);
            $this->dispatcher->dispatch(UserRegisteredEvent::NAME, $event);

            $entityManager->commit();
            $this->addFlash('success', 'You have successfully registered');

            return $this->redirectToRoute('account_index');
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', 'Registration failed');
        }

        return ['registrationForm' => $form->createView()];
    }
}
