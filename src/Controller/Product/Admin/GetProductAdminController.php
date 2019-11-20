<?php
declare(strict_types=1);

namespace App\Controller\Product\Admin;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class GetProductAdminController extends AbstractController
{
    private $repo;

    public function __construct(ProductRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @Route("/admin/product/{id}", name="show_product", requirements={"id": "[a-zA-Z0-9\-]{36,}"})
     * @Template("product/admin/show.html.twig")
     * @param string $id
     * @return array
     */
    public function showProductAction(string $id)
    {
        $product = $this->repo->find($id);
        return ['product' => $product];
    }
}


