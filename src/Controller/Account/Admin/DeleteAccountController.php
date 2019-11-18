<?php
declare(strict_types=1);

namespace App\Controller\Account\Admin;

use App\Repository\AccountRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


class DeleteAccountController extends AbstractController
{
    private $repo;

    public function __construct(AccountRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @Route("account/remove/{id}", name="delete_account", requirements={"id": "[a-zA-Z0-9\-]{36,}"})
     * @param string $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeAccount(string $id)
    {
        $account = $this->repo->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($account);
        $entityManager->flush();
        $this->addFlash('success', 'Account has been removed');
        return $this->redirectToRoute('admin_account_index');
    }
}


