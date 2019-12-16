<?php

namespace App\Controller;

use App\Entity\Uzsakymas;
use App\Form\UzsakymasType;
use App\Repository\UzsakymasRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/uzsakymas")
 */
class UzsakymasController extends AbstractController
{
    /**
     * @Route("/", name="uzsakymas_index", methods={"GET"})
     */
    public function index(UzsakymasRepository $uzsakymasRepository): Response
    {
        return $this->render('uzsakymas/index.html.twig', [
            'uzsakymas' => $uzsakymasRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="uzsakymas_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $sessionVal = $this->get('session')->get('productsInOrder');

        $uzsakyma = new Uzsakymas();
        $form = $this->createForm(UzsakymasType::class, $uzsakyma);
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

            return $this->redirectToRoute('uzsakymas_index');
        }

        return $this->render('uzsakymas/new.html.twig', [
            'uzsakyma' => $uzsakyma,
            'products'=>$sessionVal,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="uzsakymas_show", methods={"GET"})
     */
    public function show(Uzsakymas $uzsakyma): Response
    {
        return $this->render('uzsakymas/show.html.twig', [
            'uzsakyma' => $uzsakyma,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="uzsakymas_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Uzsakymas $uzsakyma): Response
    {
        $form = $this->createForm(UzsakymasType::class, $uzsakyma);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('uzsakymas_index');
        }

        return $this->render('uzsakymas/edit.html.twig', [
            'uzsakyma' => $uzsakyma,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="uzsakymas_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Uzsakymas $uzsakyma): Response
    {
        if ($this->isCsrfTokenValid('delete'.$uzsakyma->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($uzsakyma);
            $entityManager->flush();
        }

        return $this->redirectToRoute('uzsakymas_index');
    }
}
