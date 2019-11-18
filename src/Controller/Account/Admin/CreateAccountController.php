<?php
declare(strict_types=1);

namespace App\Controller\Account\Admin;

use App\Entity\Account;
use App\Form\AccountFormType;
use App\Repository\AccountRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;


class CreateAccountController extends AbstractController
{
    private $repo;

    public function __construct(AccountRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @Route("/admin/account/create", name="create_account")
     * @Method({"GET", "POST"})
     * @Template("account/admin/create.html.twig")
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     */
    public function createAccountAction(Request $request)
    {
        $account = new Account();
        $form = $this->createForm(AccountFormType::class, $account);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $account->setCreatedAt(new \DateTime());
            $account->setUpdatedAt(new \DateTime());
            $entityManager->persist($account);
            $entityManager->flush();

            $this->addFlash('success', 'Account created');

            //Sends email to the user with login link
            return $this->redirectToRoute('admin_account_index');
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Invalid data');
        }

        return ['form' => $form->createView()];
    }
}


