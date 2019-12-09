<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\OrderFormType;
use App\Repository\OrderRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;


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
     * @IsGranted("ROLE_USER")
     */
    public function listOrders()
    {
        $orders = $this->repo->findAll();

        return ['orders' => $orders];
    }

    /**
     * @Route("/admin/orders/personal", name="order_personal_index")
     * @Method({"GET"})
     * @Template("order/index.html.twig")
     * @return array
     * @IsGranted("ROLE_CRAFTSMAN")
     */
    public function listPersonalOrders()
    {
        $orders = $this->repo->findByCraftsman($this->getUser()->getId());

        return ['orders' => $orders];
    }

    /**
     * @Route("/admin/order/remove/{id}", name="remove_order", requirements={"id"="\d+"})
     * @IsGranted("ROLE_USER")
     */
    public function removeOrder($id)
    {
        $order = $this->getDoctrine()->getRepository(Order::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($order);
        $entityManager->flush();
        $this->addFlash('success', 'Užsakymas pašalintas');
        return $this->redirectToRoute('order_index');
    }

    /**
     * @Route("/admin/order/create", name="create_order")
     * @Method({"GET", "POST"})
     * @Template("order/create.html.twig")
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * @IsGranted("ROLE_USER")
     */
    public function createOrder(Request $request)
    {

        $order = new Order();
        $form = $this->createForm(OrderFormType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $order->setCompleted(false);
            $order->setCustomer($this->getUser());
            $order->setCraftsman(null);

            $entityManager->persist($order);
            $entityManager->flush();

            $this->addFlash('success', 'Užsakymas sukurtas');

            //Sends email to the user with login link
            return $this->redirectToRoute('order_index');
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Blogi duomenys');
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/admin/order/edit/{id}", name="edit_order", requirements={"id"="\d+"})
     * @Method({"GET", "POST"})
     * @Template("order/edit.html.twig")
     * @IsGranted("ROLE_CRAFTSMAN")
     */
    public function editOrder(Request $request, $id)
    {
        $order = $this->getDoctrine()->getRepository(Order::class)->find($id);
        $form = $this->createForm(OrderFormType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('order_index');
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/admin/order/{id}", name="show_order", requirements={"id"="\d+"})
     * @Template("order/show.html.twig")
     * @IsGranted("ROLE_USER")
     */
    public function showOrder($id)
    {
        $order = $this->getDoctrine()->getRepository(Order::class)->find($id);
        return ['order' => $order];
    }

    /**
     * @Route("/admin/order/complete/{id}", name="complete_order", requirements={"id"="\d+"})
     * @Method({"GET", "POST"})
     * @IsGranted("ROLE_CRAFTSMAN")
     */
    public function completeOrder(Request $request, $id)
    {
        $order = $this->getDoctrine()->getRepository(Order::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $order->setCompleted(true);
        $entityManager->persist($order);
        $entityManager->flush();
        $this->addFlash('success', 'Užsakymas atliktas');

        return $this->redirectToRoute('order_index');
    }

    /**
     * @Route("/admin/order/accept/{id}", name="accept_order", requirements={"id"="\d+"})
     * @Method({"GET", "POST"})
     * @IsGranted("ROLE_CRAFTSMAN")
     */
    public function acceptOrder(Request $request, $id)
    {
        $order = $this->getDoctrine()->getRepository(Order::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $order->setCraftsman($this->getUser());
        $entityManager->persist($order);
        $entityManager->flush();
        $this->addFlash('success', 'Užsakymas priimtas');

        return $this->redirectToRoute('order_index');
    }
}


