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
//        $user = $this->getDoctrine()->getRepository(User::class)->find(0);
//        return ['user' => $user];

//        dd($orderRepository->find(1));

        return [
            'orders' => $orderRepository->findAll(),
        ];

//        return $this->render('order/index.html.twig', [
//            'order' => $orderRepository->find(0),
//        ]);
    }

    /**
     * @Route("admin/new", name="order_new", methods={"GET","POST"})
     * @Template("admin/order/create.html.twig")
     */
    public function new(Request $request)
    {
        $sessionVal = $this->get('session')->get('productsInOrder');

        $order = new Order();
        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);

        $order->setCreatedAt(new \DateTime());
        $order->setUser($this->getUser());

        $entityManager = $this->getDoctrine()->getManager();
        if ($form->isSubmitted() && $form->isValid()) {
            if($sessionVal!=null) {
                foreach ($sessionVal as $product) {
                    $entityManager->persist($product);
                    $order->addProduct($product);
                }
            }
            $entityManager->persist($order);
            //only user, not admin
            $user =$this->getUser();
            if($user!=null){
                $user->addUzsakyma($order);
                $entityManager->persist($user);
            }
            $entityManager->flush();

            return $this->redirectToRoute('order_index');
        }

        return [
            'order' => $order,
            'products'=>$sessionVal,
            'form' => $form->createView(),
        ];
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

//    /**
//     * @Route("/admin/user/remove/{id}", name="remove_user", requirements={"id"="\d+"})
//     * @return \Symfony\Component\HttpFoundation\RedirectResponse
//     */
//    public function removeUser($id)
//    {
//        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
//        $entityManager = $this->getDoctrine()->getManager();
//        $entityManager->remove($user);
//        $entityManager->flush();
//        $this->addFlash('success', 'User has been removed');
//        return $this->redirectToRoute('user_index');
//    }
}
