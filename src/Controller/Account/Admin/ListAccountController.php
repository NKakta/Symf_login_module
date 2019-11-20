<?php
declare(strict_types=1);

namespace App\Controller\Account\Admin;

use App\Form\Account\SearchAccountFormType;
use App\Model\AccountFilterModel;
use App\Repository\AccountRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;


class ListAccountController extends AbstractController
{
    private $repo;

    public function __construct(AccountRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @Route("/admin/accounts", name="admin_account_index")
     * @Method({"GET"})
     * @Template("account/admin/index.html.twig")
     * @return array
     */
    public function listAccounts(Request $request, PaginatorInterface $paginator)
    {
        $filter = new AccountFilterModel();
        $form = $this->createForm(SearchAccountFormType::class, $filter, ['method' => 'GET']);
        $form->handleRequest($request);

        $result = $paginator->paginate(
            $this->repo->getAllQuery($filter),
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 15)
        );

        return ['accounts' => $result, 'form' => $form->createView()];
    }
}


