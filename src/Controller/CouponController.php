<?php

namespace App\Controller;

use App\Entity\Coupon;
use App\Form\CouponFormType;
use App\Repository\CouponRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CouponController extends AbstractController
{
    private $repo;

    public function __construct(CouponRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @Route("/admin/coupon", name="coupons")
     * @Method({"GET"})
     * @Template("coupon/index.html.twig")
     */
    public function index()
    {
        $coupons = $this->repo->findAll();

        return ['coupons' => $coupons];
    }

    /**
     * @Route("/admin/coupon/filtered", name="filter_coupon")
     * @Method({"GET"})
     * @Template("coupon/index.html.twig")
     * @param Request $request
     * @return array
     */
    public function indexFiltered(Request $request)
    {
        $coupons = $this->repo->findAll();
        $date = $request->query->get('filter');
        $date = strtotime($date);

        $filteredCoupons = [];
        foreach ($coupons as $coupon) {
            if ($date < $coupon->getValidDate()->getTimestamp()) {
                array_push($filteredCoupons, $coupon);
            }
        }

        return ['coupons' => $filteredCoupons];
    }

    /**
     * @Route("/admin/coupon/create", name="create_coupon")
     * @Method({"GET", "POST"})
     * @Template("coupon/create.html.twig")
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createCoupon(Request $request)
    {
        $coupon = new Coupon();
        $form = $this->createForm(CouponFormType::class, $coupon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($coupon);
            $entityManager->flush();
            $this->addFlash('success', 'Coupon created');
            return $this->redirectToRoute('coupons');
        }
        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Invalid data');
        }
        return ['form' => $form->createView()];
    }

    /**
     * @Route("/admin/coupon/edit/{id}", name="edit_coupon", requirements={"id"="\d+"})
     * @Method({"GET", "POST"})
     * @Template("coupon/edit.html.twig")
     */
    public function editAdAction(Request $request, $id)
    {
        $ad = $this->getDoctrine()->getRepository(Coupon::class)->find($id);
        $form = $this->createForm(CouponFormType::class, $ad);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('coupons');
        }
        return ['form' => $form->createView()];
    }

    /**
     * @Route("/admin/coupon/{id}", name="show_coupon", requirements={"id"="\d+"})
     * @Template("coupon/show.html.twig")
     */
    public function showCouponAction($id)
    {
        $coupon = $this->getDoctrine()->getRepository(Coupon::class)->find($id);
        return ['coupon' => $coupon];
    }

    /**
     * @Route("admin/coupon/remove/{id}", name="remove_coupon", requirements={"id"="\d+"})
     */
    public function removeCoupon($id)
    {
        $coupon = $this->getDoctrine()->getRepository(Coupon::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($coupon);
        $entityManager->flush();
        $this->addFlash('success', 'Coupon has been removed');
        return $this->redirectToRoute('coupons');
    }

    /**
     * @Route("admin/coupon/activate/{id}", name="activate_coupon", requirements={"id"="\d+"})
     */
    public function activateCoupon($id)
    {
        $coupon = $this->getDoctrine()->getRepository(Coupon::class)->find($id);
        $coupon->setActive(true);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($coupon);
        $entityManager->flush();
        $this->addFlash('success', 'Coupon has been activated');
        return $this->redirectToRoute('coupons');
    }
}
