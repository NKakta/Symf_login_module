<?php
declare(strict_types=1);

namespace App\Controller\Category\Admin;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class GetCategoryAdminController extends AbstractController
{
    private $repo;

    public function __construct(CategoryRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @Route("/admin/category/{id}", name="show_category", requirements={"id": "[a-zA-Z0-9\-]{36,}"})
     * @Template("category/admin/show.html.twig")
     * @param string $id
     * @return array
     */
    public function showCategoryAction(string $id)
    {
        $category = $this->repo->find($id);
        return ['category' => $category];
    }
}


