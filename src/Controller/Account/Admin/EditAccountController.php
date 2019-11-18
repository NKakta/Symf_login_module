<?php
declare(strict_types=1);

namespace App\Controller\Account\Admin;

use App\Form\AccountFormType;
use App\Repository\AccountRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class EditAccountController extends AbstractController
{
    private $repo;

    public function __construct(AccountRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @Route("/admin/account/edit/{id}", name="edit_account", requirements={"id": "[a-zA-Z0-9\-]{36,}"})
     * @Method({"GET", "POST"})
     * @Template("account/admin/edit.html.twig")
     */
    public function editAccountAction(Request $request, $id)
    {
        $account = $this->repo->find($id);
        $form = $this->createForm(AccountFormType::class, $account);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('admin_account_index');
        }

        return ['form' => $form->createView()];
    }
}


