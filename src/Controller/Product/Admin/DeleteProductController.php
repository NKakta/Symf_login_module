<?php
declare(strict_types=1);

namespace App\Controller\Product\Admin;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


class DeleteProductController extends AbstractController
{
    private $repo;

    public function __construct(ProductRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @Route("product/remove/{id}", name="delete_product", requirements={"id": "[a-zA-Z0-9\-]{36,}"})
     * @param string $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeProduct(string $id)
    {
        $product = $this->repo->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($product);
        $entityManager->flush();
        $this->addFlash('success', 'Product has been removed');
        return $this->redirectToRoute('admin_product_index');
    }
}


