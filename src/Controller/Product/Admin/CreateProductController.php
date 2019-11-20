<?php
declare(strict_types=1);

namespace App\Controller\Product\Admin;

use App\Entity\Product;
use App\Form\Product\ProductFormType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;


class CreateProductController extends AbstractController
{
    /**
     * @Route("/admin/product/create", name="create_product")
     * @Method({"GET", "POST"})
     * @Template("product/admin/create.html.twig")
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     */
    public function createProductAction(Request $request)
    {
        $product = new Product();
        $form = $this->createForm(ProductFormType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $product->setCreatedAt(new \DateTime());
            $product->setUpdatedAt(new \DateTime());
            $entityManager->persist($product);
            $entityManager->flush();

            $this->addFlash('success', 'Product created');

            return $this->redirectToRoute('admin_product_index');
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Invalid data');
        }

        return ['form' => $form->createView()];
    }
}


