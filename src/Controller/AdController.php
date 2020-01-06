<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdFormType;
use App\Repository\AdRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


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
     * @Template("ads/index.html.twig")
     * @return array
     * Sidebar ads paspaudus
     */
    public function listAds()
    {
        $ads = $this->repo->findAll();
        return ['ads' => $ads];
    }

    /**
     * @Route("/admin/ad/create", name="create_ad")
     * @Method({"GET", "POST"})
     * @Template("ads/create.html.twig")
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * Administratorius paspaudzia create prie reklamos
     */
    public function createAd(Request $request)
    {
        $ad = new Ad();
        $form = $this->createForm(AdFormType::class, $ad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            //sukuriama reklama unconfirmed
            //iskart uzdedu setIp numberius tam kad galeciau naudoti lyginimui duomenu bazeje kai reikes saraso pagal mano ip bet taip pat issaugau orginalia info i lentele
            $ad->setConfirmed(false);
            $ad->setIpFromNumber(ip2long($ad->getIpFrom()));
            $ad->setIpToNumber(ip2long($ad->getIpTo()));

            $entityManager->persist($ad);
            $entityManager->flush();
            $this->addFlash('success', 'ad created');

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
     * @Template("ads/edit.html.twig")
     * Administratorius paspaudzia mygtuka edit prie reklamos
     */
    public function editAd(Request $request, $id)
    {
        $ad = $this->getDoctrine()->getRepository(Ad::class)->find($id);
        $form = $this->createForm(AdFormType::class, $ad);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            //paredagavus reikia pakeisti ir ip number fieldus kitaip jie nesikeis
            $ad->setIpFromNumber(ip2long($ad->getIpFrom()));
            $ad->setIpToNumber(ip2long($ad->getIpTo()));

            $entityManager->flush();
            return $this->redirectToRoute('ad_index');
        }
        return ['form' => $form->createView()];
    }

    /**
     * @Route("/admin/ad/{id}", name="show_ad", requirements={"id"="\d+"})
     * @Template("ads/show.html.twig")
     * Administratorius paspaudzia mygtuka show prie reklamos
     */
    public function showAd($id)
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
        return $this->redirectToRoute('ad_index');
    }

    /**
     * @Route("admin/ad/accept/{id}", name="confirm_ad", requirements={"id"="\d+"})
     * @IsGranted("ROLE_ADMIN")
     * Administratorius paspaudzia mygtuka confirm prie reklamos
     */
    public function confirmAd($id)
    {
        /* @var Ad $ad */
        $ad = $this->getDoctrine()->getRepository(Ad::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();

        $ad->setConfirmed(true);

        $entityManager->persist($ad);

        $entityManager->flush();
        $this->addFlash('success', 'Ad has been accepted');
        return $this->redirectToRoute('ad_index');
    }

    

    /**
     * @Route("admin/ads/list", name="list_ad")
     * @Template("ads/index.html.twig")
     * @IsGranted("ROLE_ADMIN")
     * Traukia filtruotas reklamas pagal mano ip dabartini
     */
    public function listAdsForUsers(Request $request)
    {
        //1. Gaunu ip is $_server
        //2. Paverciu i skaiciu su ip2long kad galeciau lyginti
        //3. Gaunu atitinkamu adsu array
        $ads = $this->repo->findByIp(ip2long($_SERVER['REMOTE_ADDR']));

        return ['ads' => $ads];
    }

    /**
     * @Route("/", name="home")
     * @Template("home.html.twig")
     * @IsGranted("ROLE_USER")
     * Traukia filtruotas reklamas pagal mano ip dabartini
     */
    public function listAdsForAllUsers(Request $request)
    {
        //1. Gaunu ip is $_server
        //2. Paverciu i skaiciu su ip2long kad galeciau lyginti
        //3. Gaunu atitinkamu adsu array
        $ads = $this->repo->findByIp(ip2long($_SERVER['REMOTE_ADDR']));

        return ['ads' => $ads];
    }
}


