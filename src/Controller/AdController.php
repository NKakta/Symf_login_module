<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdFormType;
use App\Repository\AdRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdController extends AbstractController
{

    private $repo;

    public function __construct(AdRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @Route("/admin/ads", name="ad_index")
     * @Method({"GET"})
     * @Template("ad/index.html.twig")
     * @return array
     */
    public function listAds()
    {
        $ads = $this->repo->findAll();

        return ['ads' => $ads];
    }

    /**
     * @Route("/admin/ads/crappy", name="ad_crappy")
     * @Method({"GET"})
     * @Template("ad/ads.html.twig")
     * @return array
     */
    public function listAdsCrappy()
    {
        $ads = $this->repo->findAll();

        return ['ads' => $ads];
    }

    /**
     * @Route("/admin/ad/create", name="create_ad")
     * @Method({"GET", "POST"})
     * @Template("ad/create.html.twig")
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAdAction(Request $request)
    {
        $ad = new Ad();
        $form = $this->createForm(AdFormType::class, $ad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($ad);
            $ad->setViews(0);
            $entityManager->flush();
            $this->addFlash('success', 'Ad created');
            //Sends email to the user with login link
            return $this->redirectToRoute('ad_index');
        }
        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Invalid data');
        }
        return ['form' => $form->createView()];
    }

    /**
     * @Route("/admin/ad/edit/{id}", name="edit_ad", requirements={"id"="\d+"})
     * @Method({"GET", "POST"})
     * @Template("ad/edit.html.twig")
     */
    public function editAdAction(Request $request, $id)
    {
        $ad = $this->getDoctrine()->getRepository(Ad::class)->find($id);
        $form = $this->createForm(AdFormType::class, $ad);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->flush();
            return $this->redirectToRoute('ad_index');
        }
        return ['form' => $form->createView()];
    }

    /**
     * @Route("/admin/ad/{id}", name="show_ad", requirements={"id"="\d+"})
     * @Template("ad/show.html.twig")
     */
    public function showAdAction($id)
    {
        $ad = $this->getDoctrine()->getRepository(Ad::class)->find($id);
        return ['ad' => $ad];
    }

    /**
     * @Route("admin/ad/remove/{id}", name="remove_ad", requirements={"id"="\d+"})
     */
    public function removeAd($id)
    {
        $ad = $this->getDoctrine()->getRepository(Ad::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($ad);
        $entityManager->flush();
        $this->addFlash('success', 'Ad has been removed');
        return $this->redirectToRoute('department_index');
    }

}
