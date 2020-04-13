<?php
declare(strict_types=1);

namespace App\Controller\Client;

use App\Model\Cart;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AbstractClientController extends AbstractController
{
    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function getCart()
    {
        $cart =  $this->session->get('cart');

        if (!$cart instanceof Cart) {
            $cart = new Cart();
        }

        return $cart;
    }

    public function handleException(\Throwable $e)
    {
        $cart = $this->getCart();
        $this->session->set('cart', $cart);
        $this->addFlash('error',$e->getMessage());
    }
}
