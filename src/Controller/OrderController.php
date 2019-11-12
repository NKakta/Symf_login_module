<?php

namespace App\Controller;

use App\Entity\Order;
use App\Repository\OrderRepository;
use App\Repository\PictureRepository;
use App\Repository\VacationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


class OrderController extends AbstractController
{
    private $repo;

    public function __construct(OrderRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @Route("/admin/orders", name="order_index")
     * @Method({"GET"})
     * @Template("order/index.html.twig")
     * @return array
     */
    public function listOrders()
    {
        $orders = $this->repo->findAll();

        return ['orders' => $orders];
    }

    /**
     * @Route("/admin/order/remove/{id}", name="remove_order", requirements={"id"="\d+"})
     */
    public function removeOrder($id)
    {
        $order = $this->getDoctrine()->getRepository(Order::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($order);
        $entityManager->flush();
        $this->addFlash('success', 'Order has been removed');
        return $this->redirectToRoute('order_index');
    }
}


