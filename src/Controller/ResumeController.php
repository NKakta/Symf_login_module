<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Account;
use App\Form\AccountFormType;
use App\Repository\AccountRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


class ResumeController extends AbstractController
{
    private $repo;

    public function __construct(AccountRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @Route("/admin/resumes", name="resume_index")
     * @Method({"GET"})
     * @Template("account/index.html.twig")
     * @return array
     */
    public function listResumes()
    {
        $resumes = $this->repo->findAll();

        return ['resumes' => $resumes];
    }

    /**
     * @Route("/admin/account/create", name="create_resume")
     * @Method({"GET", "POST"})
     * @Template("account/create.html.twig")
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createResumeAction(Request $request)
    {

        $resume = new Account();
        $form = $this->createForm(AccountFormType::class, $resume);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $resume->setCreatedAt(new DateTime());
            $resume->setUpdatedAt(new DateTime());
            $resume->setUser($this->getUser());
            $entityManager->persist($resume);
            $entityManager->flush();

            $this->addFlash('success', 'Account created');

            //Sends email to the user with login link
            return $this->redirectToRoute('resume_index');
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Invalid data');
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/admin/account/edit/{id}", name="edit_resume", requirements={"id"="\d+"})
     * @Method({"GET", "POST"})
     * @Template("account/edit.html.twig")
     */
    public function editResumeAction(Request $request, $id)
    {
        $resume = $this->getDoctrine()->getRepository(Account::class)->find($id);
        $form = $this->createForm(AccountFormType::class, $resume);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('resume_index');
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/admin/account/{id}", name="show_resume", requirements={"id"="\d+"})
     * @Template("account/show.html.twig")
     */
    public function showResumeAction($id)
    {
        $resume = $this->getDoctrine()->getRepository(Account::class)->find($id);
        return ['account' => $resume];
    }

    /**
     * @Route("account/remove/{id}", name="remove_resume", requirements={"id"="\d+"})
     */
    public function removeResume($id)
    {
        $resume = $this->getDoctrine()->getRepository(Account::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($resume);
        $entityManager->flush();
        $this->addFlash('success', 'Account has been removed');
        return $this->redirectToRoute('resume_index');
    }
}


