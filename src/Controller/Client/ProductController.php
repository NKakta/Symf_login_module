<?php
declare(strict_types=1);

namespace App\Controller\Client;

use App\Exception\NotEnoughInStockException;
use App\Repository\ProductRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ProductController extends AbstractClientController
{
    /**
     * @var ProductRepository
     */
    private $repo;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * ProductController constructor.
     */
    public function __construct(ProductRepository $repo, SessionInterface $session)
    {
        parent::__construct($session);
        $this->session = $session;
        $this->repo = $repo;
    }

    /**
     * @Route("/client/products", name="product_client_index")
     * @Method({"GET"})
     * @Template("product/client/index.html.twig")
     * @return array
     */
    public function listProductsClient()
    {
        $products = $this->repo->findAll();
        $cart = $this->getCart();

        return [
            'products' => $products,
            'cart' => $cart
        ];
    }

    /**
     * @Route("/product/{id}/add-to-cart", name="product_add_to_cart")
     * @Method({"GET"})
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addToCart(Request $request, $id)
    {
        $product = $this->repo->find($id);
        $cart = $this->getCart();

        try {
            $cart->add($product);
        } catch (NotEnoughInStockException $e) {
            $this->handleException($e);
            return $this->redirectToRoute('product_client_index');
        }

        $this->session->set('cart', $cart);

        return $this->redirectToRoute('product_client_index');
    }

    /**
     * @Route("/product/{id}/remove-one-from-cart", name="cart_remove_one")
     * @Method({"GET"})
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function reduceByOneFromCart(Request $request, $id)
    {
        $product = $this->repo->find($id);
        $cart = $this->getCart();

        try {
            $cart->reduceByOne($product);
        } catch (NotEnoughInStockException $e) {
            $this->handleException($e);
            return $this->redirectToRoute('product_client_index');
        }

        $this->session->set('cart', $cart);

        return $this->redirectToRoute('product_client_index');
    }

    /**
     * @Route("/product/{id}/add-one-to-cart", name="cart_add_one")
     * @Method({"GET"})
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function increaseByOneFromCart(Request $request, $id)
    {
        $product = $this->repo->find($id);
        $cart = $this->getCart();

        try {
            $cart->add($product);
        } catch (NotEnoughInStockException $e) {
            $this->handleException($e);
            return $this->redirectToRoute('product_client_index');
        }

        $this->session->set('cart', $cart);

        return $this->redirectToRoute('product_client_index');
    }

    /**
     * @Route("/product/{id}/remove-item-from-cart", name="cart_remove_item")
     * @Method({"GET"})
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeItemFromCart($id) {
        $product = $this->repo->find($id);
        $cart = $this->getCart();

        $cart->removeItem($product);

        $this->session->set('cart', $cart);

        return $this->redirectToRoute('product_client_index');
    }

    /**
     * @Route("/cart/clear", name="cart_clear")
     * @Method({"GET"})
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function clearCart()
    {
        $this->session->clear();
        return $this->redirectToRoute('product_client_index');
    }
}


