<?php
declare(strict_types=1);

namespace App\Controller\Account\Admin;

use App\Repository\AccountRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class GetAccountAdminController extends AbstractController
{
    private $repo;

    public function __construct(AccountRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @Route("/admin/account/{id}", name="show_account", requirements={"id": "[a-zA-Z0-9\-]{36,}"})
     * @Template("account/admin/show.html.twig")
     * @param string $id
     * @return array
     */
    public function showAccountAction(string $id)
    {
        $account = $this->repo->find($id);
        return ['account' => $account];
    }
}


