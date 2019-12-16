<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductFormType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends AbstractController
{
    private $repo;

    public function __construct(ProductRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @Route("/admin/product/statistics", name="product_statistics")
     * @Method({"GET"})
     * @Template("product/statistics.html.twig")
     * @return array
     */
    public function showProductStatistics()
    {
        $products = $this->repo->findAll();

        return ['productCount' => count($products)];
    }

    /**
     * @Route("/admin/product", name="product_index")
     * @Method({"GET"})
     * @Template("product/index.html.twig")
     * @return array
     */
    public function listProducts()
    {
        $products = $this->repo->findAll();

        return ['products' => $products];
    }

    /**
     * @Route("/admin/product/create", name="create_product")
     * @Method({"GET", "POST"})
     * @Template("product/create.html.twig")
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createProductAction(Request $request)
    {
        $product = new Product();
        $product->setDateTo(new \DateTime());
        $product->setDateFrom(new \DateTime());
        $form = $this->createForm(ProductFormType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();
            $this->addFlash('success', 'Product created');
            //Sends email to the user with login link
            return $this->redirectToRoute('product_index');
        }
        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Invalid data');
        }
        return ['form' => $form->createView()];
    }


    /**
     * @Route("admin/product/add/order/{id}", name="add_product_order", requirements={"id"="\d+"})
     */
    public function addProductToOrder($id)
    {
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
        // Get Value from session
        $sessionVal = $this->get('session')->get('productsInOrder');
        // Append value to retrieved array.
        $sessionVal[] = $product;
        // Set value back to session
        $this->get('session')->set('productsInOrder', $sessionVal);
        dump($sessionVal);

        $this->addFlash('success', 'Product has been added to order');
        return $this->redirectToRoute('product_index');
    }

    /**
     * @Route("admin/product/remove/order/{id}", name="remove_product_order", requirements={"id"="\d+"})
     */
    public function removeProductToOrder($id)
    {
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
        // Get Value from session
        $sessionVal = $this->get('session')->get('productsInOrder');


        dump($sessionVal);
        dump($product);
        // object exists in array; do something
        $item = null;
        $id = $product->getId();
        $sessionVal = array_filter($sessionVal, function($v) use ($id) { return $v->getId() != $id; });
        dump($sessionVal);
        $this->get('session')->set('productsInOrder', $sessionVal);

        $this->addFlash('danger', 'Product has been removed successfully');
        return $this->redirectToRoute('uzsakymas_index');
    }

    function findObjectById($id)
    {
        $array = array( /* your array of objects */);

        foreach ($array as $element) {
            if ($id == $element->id) {
                return $element;
            }
        }

        return false;
    }

    /**
     * @Route("/admin/product/edit/{id}", name="edit_product", requirements={"id"="\d+"})
     * @Method({"GET", "POST"})
     * @Template("product/edit.html.twig")
     */
    public function editProductAction(Request $request, $id)
    {
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
        $form = $this->createForm(ProductFormType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $product->setDateTo(new \DateTime());
            $product->setDateFrom(new \DateTime());

            $entityManager->flush();
            return $this->redirectToRoute('product_index');
        }
        return ['form' => $form->createView()];
    }

    /**
     * @Route("/admin/product/{id}", name="show_product", requirements={"id"="\d+"})
     * @Template("product/show.html.twig")
     */
    public function showProductAction($id)
    {
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
        return ['product' => $product];
    }

    /**
     * @Route("admin/product/remove/{id}", name="remove_product", requirements={"id"="\d+"})
     */
    public function removeProduct($id)
    {
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($product);
        $entityManager->flush();

        $this->addFlash('success', 'Product has been removed');
        return $this->redirectToRoute('product_index');
    }

    /**
     * @Route("admin/product/remove/{id}", name="remove_product", requirements={"id"="\d+"})
     */
    public function removeViaTimer($id)
    {
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($product);
        $entityManager->flush();

        $this->addFlash('success', 'Product has been removed');
        return $this->redirectToRoute('product_index');
    }
}


