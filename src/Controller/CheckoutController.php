<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\Client\AbstractClientController;
use App\Entity\Order;
use App\Entity\Product;
use App\Exception\NotEnoughInStockException;
use App\Model\Cart;
use App\Model\StoredItem;
use App\UseCase\Payment\PurchaseUseCase;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CheckoutController extends AbstractClientController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var PurchaseUseCase
     */
    private $purchaseUseCase;

    public function __construct(
        EntityManagerInterface $em,
        SessionInterface $session,
        PurchaseUseCase $purchaseUseCase
    ) {
        parent::__construct($session);
        $this->em = $em;
        $this->session = $session;
        $this->purchaseUseCase = $purchaseUseCase;
    }

    /**
     * @Route("/checkout", name="checkout")
     * @Method({"POST"})
     */
    public function checkout(Request $request)
    {
        $cart = $this->getCart();

        try {
            $cart->validate();
        } catch (NotEnoughInStockException $e) {
            $this->handleException($e);
            return $this->redirectToRoute('product_client_index');
        }




        $order = $this->createOrderFromCart($cart);

        $response = $this->purchaseUseCase->purchase($order);

        if ($response->isRedirect()) {
            $this->em->persist($order);
            $this->em->flush();
            $response->redirect();
        }
        dd("not redirect");

//        $this->updateProductQuantities($cart);

        $this->em->persist($order);
        $this->em->flush();

        return $this->redirectToRoute('product_client_index');
    }

    protected function createOrderFromCart(Cart $cart): Order
    {
        $order = new Order();
        $order
            ->setTotalPrice((string)$cart->getTotalPrice())
            ->setProducts($cart->getProducts())
            ->setEmailNotification(false)
            ->setNumber((int)uniqid())
            ->setStatus(Order::STATUS_NOT_PAYED)
            ->setCreatedAt(new \DateTime())
        ;

        return $order;
    }

    protected function updateProductQuantities(Cart $cart)
    {
        /* @var StoredItem $item */
        foreach ($cart->getItems() as $item) {
            /* @var Product $product */
            $product = $item->getItem();
            $product->setReservedQuantity($product->getReservedQuantity() + $item->getQuantity());
            $this->em->persist($product);
        }
    }

}


