<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\Client\AbstractClientController;
use App\Entity\Order;
use App\Entity\Product;
use App\Exception\NotEnoughInStockException;
use App\Model\StoredItem;
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

    public function __construct(
        EntityManagerInterface $em,
        SessionInterface $session
    ) {
        parent::__construct($session);
        $this->em = $em;
        $this->session = $session;
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

        $products = $cart->getProducts();

        /* @var StoredItem $item */
        foreach ($cart->getItems() as $item) {
            /* @var Product $product */
            $product = $item->getItem();
            $product->setReservedQuantity($product->getReservedQuantity() + $item->getQuantity());
            $this->em->persist($product);
        }

        $this->em->flush();

        $order = new Order();
        $order
            ->setTotalPrice((string)$cart->getTotalPrice())
            ->setProducts($products)
            ->setEmailNotification(false)
            ->setNumber((int)uniqid())
            ->setStatus(Order::STATUS_NOT_PAYED)
            ->setCreatedAt(new \DateTime())
        ;

        $this->em->persist($order);
        $this->em->flush();

        return $this->redirectToRoute('product_client_index');
    }
}


