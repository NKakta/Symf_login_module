<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Order;
use App\Form\OrderType;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\User;
use App\Event\UserRegisteredEvent;
use App\Form\SearchEmailFormType;
use App\Form\UserFormType;
use App\Form\UserSettingFormType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Route("/order")
 */
class OrderController extends AbstractController
{
    /**
     * @Route("/admin", name="order_index", methods={"GET"})
     * @Template("admin/order/index.html.twig")
     */
    public function index(OrderRepository $orderRepository)
    {
        return [
            'orders' => $orderRepository->findAll(),
        ];
    }

    /**
     * @Route("admin/new", name="order_new", methods={"GET","POST"})
     * @Template("admin/order/create.html.twig")
     */
    public function createProduct(Request $request)
    {
        $order = new Order();
        $order->setCreatedAt(new \DateTime());

        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($order);
            $entityManager->flush();
            $this->addFlash('success', 'Product created');
            return $this->redirectToRoute('order_index');
        }
        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Invalid data');
        }
        return ['form' => $form->createView()];
    }

    /**
     * @Route("/admin/{id}", name="order_show", methods={"GET"})
     * @Template("admin/order/show.html.twig")
     */
    public function show(Order $order)
    {
        return [
            'order' => $order,
        ];
    }

    /**
     * @Route("/admin/{id}/edit", name="order_edit", methods={"GET","POST"})
     * @Template("admin/order/edit.html.twig")
     */
    public function edit(Request $request, Order $order): Response
    {
        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('order_index');
        }

        return $this->render('admin/order/edit.html.twig', [
            'order' => $order,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/order/remove/{id}", name="order_delete", methods={"DELETE"})
     * @Template("admin/order/delete.html.twig")
     */
    public function delete(Request $request, Order $order): Response
    {
        if ($this->isCsrfTokenValid('delete'.$order->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($order);
            $entityManager->flush();
        }

        return $this->redirectToRoute('delete.html.twig');
    }
}
