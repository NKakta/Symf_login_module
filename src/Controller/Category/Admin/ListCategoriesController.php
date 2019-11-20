<?php
declare(strict_types=1);

namespace App\Controller\Category\Admin;

use App\Form\Category\SearchCategoryFormType;
use App\Model\CategoryFilterModel;
use App\Repository\CategoryRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;


class ListCategoriesController extends AbstractController
{
    private $repo;

    public function __construct(CategoryRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @Route("/admin/categories", name="admin_category_index")
     * @Method({"GET"})
     * @Template("category/admin/index.html.twig")
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return array
     */
    public function listCategories(Request $request, PaginatorInterface $paginator)
    {
        $filter = new CategoryFilterModel();
        $form = $this->createForm(SearchCategoryFormType::class, $filter, ['method' => 'GET']);
        $form->handleRequest($request);

        $result = $paginator->paginate(
            $this->repo->getAllQuery($filter),
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 15)
        );

        return ['categories' => $result, 'form' => $form->createView()];
    }
}


