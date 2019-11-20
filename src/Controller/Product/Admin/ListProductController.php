<?php
declare(strict_types=1);

namespace App\Controller\Product\Admin;

use App\Form\Product\SearchProductFormType;
use App\Model\ProductFilterModel;
use App\Repository\ProductRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;


class ListProductController extends AbstractController
{
    private $repo;

    public function __construct(ProductRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @Route("/admin/products", name="admin_product_index")
     * @Method({"GET"})
     * @Template("product/admin/index.html.twig")
     * @return array
     */
    public function listProducts(Request $request, PaginatorInterface $paginator)
    {
        $filter = new ProductFilterModel();
        $form = $this->createForm(SearchProductFormType::class, $filter, ['method' => 'GET']);
        $form->handleRequest($request);

        $result = $paginator->paginate(
            $this->repo->getAllQuery($filter),
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 15)
        );

        return ['products' => $result, 'form' => $form->createView()];
    }
}


