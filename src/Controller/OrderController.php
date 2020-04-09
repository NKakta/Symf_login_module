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

/**
 * @Route("/order")
 */
class OrderController extends AbstractController
{
    /**
     * @Route("/", name="order_index", methods={"GET"})
     */
    public function index(OrderRepository $orderRepository): Response
    {
        return $this->render('order/index.html.twig', [
            'order' => $orderRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="order_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $sessionVal = $this->get('session')->get('productsInOrder');

        $uzsakyma = new Order();
        $form = $this->createForm(OrderType::class, $uzsakyma);
        $form->handleRequest($request);
        $entityManager = $this->getDoctrine()->getManager();
        if ($form->isSubmitted() && $form->isValid()) {
            if($sessionVal!=null) {
                foreach ($sessionVal as $product) {
                    $entityManager->persist($product);
                    $uzsakyma->addProduct($product);
                }
            }
            $entityManager->persist($uzsakyma);
            //only user, not admin
            $user =$this->getUser();
            if($user!=null){
                $user->addUzsakyma($uzsakyma);
                $entityManager->persist($user);
            }
            dump($uzsakyma);
            $entityManager->flush();

            return $this->redirectToRoute('order_index');
        }

        return $this->render('order/new.html.twig', [
            'uzsakyma' => $uzsakyma,
            'products'=>$sessionVal,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="order_show", methods={"GET"})
     */
    public function show(Order $uzsakyma): Response
    {
        return $this->render('order/show.html.twig', [
            'uzsakyma' => $uzsakyma,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="order_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Order $uzsakyma): Response
    {
        $form = $this->createForm(OrderType::class, $uzsakyma);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('order_index');
        }

        return $this->render('order/edit.html.twig', [
            'uzsakyma' => $uzsakyma,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="order_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Order $uzsakyma): Response
    {
        if ($this->isCsrfTokenValid('delete'.$uzsakyma->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($uzsakyma);
            $entityManager->flush();
        }

        return $this->redirectToRoute('order_index');
    }
}
