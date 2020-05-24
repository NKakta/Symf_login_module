<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\Client\AbstractClientController;
use App\Entity\Order;
use App\Entity\Product;
use App\Exception\NotEnoughInStockException;
use App\Model\Cart;
use App\Model\StoredItem;
use App\Repository\OrderRepository;
use App\UseCase\Payment\CompleteUseCase;
use App\UseCase\Payment\PurchaseUseCase;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CheckoutController extends AbstractClientController
{
    private $em;

    private $session;

    private $purchaseUseCase;

    private $completeUseCase;

    private $orderRepo;

    public function __construct(
        EntityManagerInterface $em,
        OrderRepository $orderRepo,
        SessionInterface $session,
        PurchaseUseCase $purchaseUseCase,
        CompleteUseCase $completeUseCase
    )
    {
        parent::__construct($session);
        $this->em = $em;
        $this->session = $session;
        $this->purchaseUseCase = $purchaseUseCase;
        $this->completeUseCase = $completeUseCase;
        $this->orderRepo = $orderRepo;
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
        $this->em->persist($order);
        $this->em->flush();

        $response = $this->purchaseUseCase->purchase($order);

        if ($response->isRedirect()) {
            $this->em->persist($order);
            $this->em->flush();
            $response->redirect();
        }

        $this->em->persist($order);
        $this->em->flush();

        return $this->redirectToRoute('product_client_index');
    }

    protected function createOrderFromCart(Cart $cart): Order
    {
        $order = new Order();
        $order
            ->setTotalPrice((string)$cart->getTotalPrice())
            ->setEmailNotification(false)
            ->setProducts(new ArrayCollection($cart->getProducts()))
            ->setNumber((int)uniqid())
            ->setStatus(Order::STATUS_NOT_PAYED)
            ->setCreatedAt(new \DateTime());

        if ($this->getUser()) {
            $order->setUser($this->getUser());
        }

        return $order;
    }

    /**
     * @Route("/checkout/{order}/complete", name="paypal_checkout_complete")
     * @Method({"GET"})
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function completeCheckout($order)
    {
        /* @var $order Order */
        $order = $this->orderRepo->findOneBy(['id' => $order]);

        $this->em = $this->getDoctrine()->getManager();

        $response = $this->completeUseCase->complete($order);

        if ($response->isSuccessful()) {
            $this->updateProductQuantities($this->getCart());
            $order->setStatus(Order::STATUS_PAYED);
            $this->em->persist($order);
            $this->em->flush();
            $this->session->clear();


            $this->addFlash('thank_you', 'Payment is sucessful with reference code ' . $response->getTransactionReference());
            return $this->redirectToRoute('product_client_index');
        }

        $this->addFlash('danger', 'Order unsuccessful!');
        return $this->redirectToRoute('product_client_index');
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


