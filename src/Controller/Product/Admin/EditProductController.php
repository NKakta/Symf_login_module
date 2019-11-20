<?php
declare(strict_types=1);

namespace App\Controller\Product\Admin;

use App\Form\Product\ProductFormType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class EditProductController extends AbstractController
{
    private $repo;

    public function __construct(ProductRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @Route("/admin/product/edit/{id}", name="edit_product", requirements={"id": "[a-zA-Z0-9\-]{36,}"})
     * @Method({"GET", "POST"})
     * @Template("product/admin/edit.html.twig")
     */
    public function editProductAction(Request $request, $id)
    {
        $product = $this->repo->find($id);
        $form = $this->createForm(ProductFormType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('admin_product_index');
        }

        return ['form' => $form->createView()];
    }
}


