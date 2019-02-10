<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


class UserController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_home")
     * @Method({"GET", "POST"})
     * @Template("admin/home.html.twig")
     * @IsGranted("ROLE_SUPERADMIN")
     */
    public function adminDashboardAction()
    {
    }

    /**
     * @Route("/admin/user", name="user_index")
     * @Method({"GET", "POST"})
     * @Template("admin/user/index.html.twig")
     * @IsGranted("ROLE_SUPERADMIN")
     */
    public function userListAction()
    {

        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        return ['users' => $users];
    }

    /**
     * @Route("/admin/user/create", name="create_user")
     * @Method({"GET", "POST"})
     * @Template("admin/user/create.html.twig")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * @IsGranted("ROLE_SUPERADMIN")
     */
    public function createUserAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

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

            return $this->redirectToRoute('create_user');
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Invalid data');
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/admin/user/edit/{id}", name="edit_user")
     * @Method({"GET", "POST"})
     * @Template("admin/user/edit.html.twig")
     * @param Request $request
     * @param $id
     * @return array
     * @IsGranted("ROLE_SUPERADMIN")
     */
    public function editUserAction(Request $request, $id)
    {
        $user = new User();
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
     * @Route("/admin/user/{id}", name="show_user")
     * @Template("admin/user/show.html.twig")
     * @param $id
     * @return array
     * @IsGranted("ROLE_SUPERADMIN")
     */
    public function showUserAction($id)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        return ['user' => $user];
    }

    /**
     * @Route("/admin/user/remove/{id}", name="remove_user")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeUser($id)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($user);
        $entityManager->flush();
        $this->addFlash('success','User has been removed');
        return $this->redirectToRoute('user_index');
    }
}