<?php

namespace App\Controller;

use App\Entity\User;
use App\Event\UserRegisteredEvent;
use App\Form\SearchEmailFormType;
use App\Form\UserFormType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;


class UserController extends AbstractController
{
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }


    /**
     * @Route("/admin", name="admin_home")
     * @Method({"GET", "POST"})
     * @Template("admin/home.html.twig")
     * @IsGranted("ROLE_ADMIN")
     */
    public function adminDashboardAction()
    {
    }

    /**
     * @Route("/admin/user", name="user_index")
     * @Method({"GET", "POST"})
     * @Template("admin/user/index.html.twig")
     * @IsGranted("ROLE_ADMIN")
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return array
     */
    public function userListAction(Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(SearchEmailFormType::class);
        $queryBuilder = $em->getRepository('App\Entity\User')->createQueryBuilder('bp');

        if ($request->query->getAlnum('email')) {
            $queryBuilder
                ->where('bp.email LIKE :email')
                ->setParameter('email', '%' . $request->query
                        ->getAlnum('email') . '%');
        }

        $query = $queryBuilder->getQuery();

        $result = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 6)
        );

        return ['users' => $result, 'form' => $form->createView()];
    }

    /**
     * @Route("/admin/user/create", name="create_user")
     * @Method({"GET", "POST"})
     * @Template("admin/user/create.html.twig")
     * @IsGranted("ROLE_ADMIN")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param EventDispatcherInterface $dispatcher
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createUserAction(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        EventDispatcherInterface $dispatcher)
    {
        $notBlankRestriction = new Assert\NotBlank();

        $user = new User();
        $user->setRole('ROLE_USER');
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $roles = $request->get('user_form')['roles'];

            $user->setRoles($roles);

            //validates if password is blank
            $plainPassword = $form->get('plainPassword')->getData();
            $errors = $this->validator->validate($plainPassword, $notBlankRestriction);

            if (count($errors)) {
                $form = $this->createForm(UserFormType::class, $user);
                $this->addFlash('danger', 'Password can not be blank');
                return ['form' => $form->setData($form->getData())->createView()];
            }

            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'User created');

            //Sends email to the user with login link
            $event = new UserRegisteredEvent($user);
            $dispatcher->dispatch(UserRegisteredEvent::NAME, $event);

            return $this->redirectToRoute('create_user');
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Invalid data');
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/admin/user/edit/{id}", name="edit_user", requirements={"id"="\d+"})
     * @Method({"GET", "POST"})
     * @Template("admin/user/edit.html.twig")
     * @IsGranted("ROLE_ADMIN")
     * @param Request $request
     * @param $id
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editUserAction(Request $request, $id)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('user_index');
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/admin/user/{id}", name="show_user", requirements={"id"="\d+"})
     * @Template("admin/user/show.html.twig")
     * @IsGranted("ROLE_ADMIN")
     * @param $id
     * @return array
     */
    public function showUserAction($id)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        return ['user' => $user];
    }

    /**
     * @Route("/admin/user/remove/{id}", name="remove_user", requirements={"id"="\d+"})
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeUser($id)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($user);
        $entityManager->flush();
        $this->addFlash('success', 'User has been removed');
        return $this->redirectToRoute('user_index');
    }

    /**
     * @Route("/admin/search/{email}", name="search_user")
     * @param $email
     * @param ValidatorInterface $validator
     * @return JsonResponse
     */
    public function searchAction($email, ValidatorInterface $validator)
    {
        $emailConstraint = new Assert\Email();
        $emailConstraint->message = 'Invalid email address';

        $errors = $validator->validate(
            $email,
            $emailConstraint
        );

        if (0 === count($errors)) {
            $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $email]);

            if ($user) {
                return new JsonResponse($user->getPublicInfo());
            }
        }
        return new JsonResponse([]);
    }
}


