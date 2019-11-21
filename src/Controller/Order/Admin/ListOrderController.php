<?php
declare(strict_types=1);

namespace App\Controller\Order\Admin;

use App\Form\Order\SearchOrderFormType;
use App\Model\OrderFilterModel;
use App\Repository\OrderRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class ListOrderController extends AbstractController
{
    private $repo;

    public function __construct(OrderRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @Route("/admin/orders", name="admin_order_index")
     * @Method({"GET"})
     * @Template("order/admin/index.html.twig")
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return array
     */
    public function listOrders(Request $request, PaginatorInterface $paginator)
    {
        $filter = new OrderFilterModel();
        $form = $this->createForm(SearchOrderFormType::class, $filter, ['method' => 'GET']);
        $form->handleRequest($request);

        $result = $paginator->paginate(
            $this->repo->getAllQuery($filter),
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 15)
        );

        return ['orders' => $result, 'form' => $form->createView()];
    }
}


