<?php

namespace App\Controller;

use App\Entity\Resume;
use App\Form\ResumeFormType;
use App\Form\SearchCvFormType;
use App\Form\SearchEmailFormType;
use App\Repository\ResumeRepository;
use DateTime;
use Dompdf\Dompdf;
use Dompdf\Options;
use Knp\Component\Pager\PaginatorInterface;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


class ResumeController extends AbstractController
{
    private $repo;

    public function __construct(ResumeRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @Route("/resumes", name="resume_index")
     * @Method({"GET"})
     * @Template("resume/index.html.twig")
     * @return array
     */
    public function listResumes()
    {
        $resumes = $this->repo->findAll();
        return ['resumes' => $resumes];
    }


    /**
     * @Route("/resume/create", name="create_resume")
     * @Method({"GET", "POST"})
     * @Template("resume/create.html.twig")
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createResumeAction(Request $request)
    {

        $resume = new Resume();
        $form = $this->createForm(ResumeFormType::class, $resume);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $resume->setCreatedAt(new DateTime());
            $resume->setUpdatedAt(new DateTime());
            $resume->setUser($this->getUser());

            $entityManager->persist($resume);
            $entityManager->flush();

            if($resume->getIsMain()== true){
                $this->getUser()->setMain($resume->getId());
                $resume->setIsMain(false);
                $entityManager->flush();
            }

            $this->addFlash('success', 'Resume created');

            return $this->redirectToRoute('resume_index');
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Invalid data');
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/searchCV", name="search_resume")
     * @Method({"GET", "POST"})
     * @Template("resume/search.html.twig")
     */
    public function searchResumeAction(Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();

        //$users = $em->getRepository(User::class)->findAll();
        $form = $this->createForm(SearchCvFormType::class);
        $queryBuilder = $em->getRepository('App\Entity\Resume')->createQueryBuilder('bp');

        if ($request->query->getAlnum('sritis')) {
            $queryBuilder
                ->where('bp.sritis LIKE :sritis')
                ->setParameter('sritis', '%' . $request->query
                        ->getAlnum('sritis') . '%');
        }

        $query = $queryBuilder->getQuery();

        $result = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 6)
        );

        return ['resumes' => $result, 'form' => $form->createView()];
    }


    /**
     * @Route("/resume/edit/{id}", name="edit_resume", requirements={"id"="\d+"})
     * @Method({"GET", "POST"})
     * @Template("resume/edit.html.twig")
     */
    public function editResumeAction(Request $request, $id)
    {
        $resume = $this->getDoctrine()->getRepository(Resume::class)->find($id);
        $form = $this->createForm(ResumeFormType::class, $resume);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            if($resume->getIsMain()== true){
                $this->getUser()->setMainNum($resume->getId());
                $resume->setIsMain(false);
                $entityManager->flush();
            }
            return $this->redirectToRoute('resume_index');
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/resume/{id}", name="show_resume", requirements={"id"="\d+"})
     * @Template("resume/show.html.twig")
     */
    public function showResumeAction($id)
    {
        $resume = $this->getDoctrine()->getRepository(Resume::class)->find($id);
        return ['resume' => $resume];
    }

    /**
     * @Route("/download/{id}", name="download_resume", requirements={"id"="\d+"})
     * @Template("resume/show.html.twig")
     */
    public function downloadResumeAction($id)
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $resume = $this->getDoctrine()->getRepository(Resume::class)->find($id);

        $html = $this->renderView('resume/download.html.twig', array('resume' => $resume));

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'landscape');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("CV.pdf", [
            "Attachment" => true
        ]);
    }

    /**
     * @Route("/resume/employer/{id}", name="showForEmployer_resume", requirements={"id"="\d+"})
     * @Method({"GET", "POST"})
     * @Template("resume/show.html.twig")
     */
    public function showResumeForEmployerAction($id)
    {
        $resume = $this->getDoctrine()->getRepository(Resume::class)->find($id);
        if($this->getUser()->getCredits() >= 10){
            $entityManager = $this->getDoctrine()->getManager();
            $this->getUser()->setCredits($this->getUser()->getCredits()-10);
            $entityManager->flush();
            return ['resume' => $resume];
        }
        else
            {
                $this->addFlash('warning', 'Nepakanka kreditų');
            }
        return $this->redirectToRoute('search_resume');
    }

    /**
     * @Route("resume/remove/{id}", name="remove_resume", requirements={"id"="\d+"})
     */
    public function removeResume($id)
    {
        $resume = $this->getDoctrine()->getRepository(Resume::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($resume);
        $entityManager->flush();
        $this->addFlash('success', 'Resume has been removed');
        return $this->redirectToRoute('resume_index');
    }
}


