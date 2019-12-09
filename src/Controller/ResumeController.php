<?php

namespace App\Controller;

use App\Entity\Resume;
use App\Entity\Tracking;
use App\Form\ResumeFormType;
use App\Form\SearchCvFormType;
use App\Form\SearchEmailFormType;
use App\Repository\ResumeRepository;
use DateTime;
use Dompdf\Dompdf;
use Dompdf\Options;
use Knp\Component\Pager\PaginatorInterface;
use phpDocumentor\Reflection\Types\This;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
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
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_EMPLOYEE')")
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
     * @IsGranted("ROLE_EMPLOYEE")
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
                //$query = $entityManager->getRepository('App\Entity\Resume');
                //$query = $entityManager->getRepository('App\Entity\Resume')
                //    ->find(2)
                 //   ->setParameter('isMain', '0');
                $this->getUser()->setMainNum($resume->getId());
                $resume->setIsMain(false);
            }
            if($this->isGranted('ROLE_EMPLOYEE')){
                $tracking = new Tracking();
                $tracking->setUser($this->getUser());
                $tracking->setAction('Sukurta nauja CV anketa');
                $tracking->setTime(new DateTime());
                $entityManager->persist($tracking);
                $entityManager->flush();
            }

            $this->addFlash('success', 'CV anketa sėkmingai sukurta');

            return $this->redirectToRoute('resume_index');
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Blogas duomenų formatas');
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/searchCV", name="search_resume")
     * @Method({"GET", "POST"})
     * @Template("resume/search.html.twig")
     * @IsGranted("ROLE_EMPLOYER")
     */
    public function searchResumeAction(Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();

        //$users = $em->getRepository(User::class)->findAll();
        $form = $this->createForm(SearchCvFormType::class);
        $queryBuilder = $em->getRepository('App\Entity\Resume')->createQueryBuilder('bp');


        if ($request->query->getAlnum('area')) {
            $queryBuilder
                ->where('bp.area LIKE :area')
                ->setParameter('area', '%' . $request->query
                        ->getAlnum('area') . '%');
        }
        if ($request->query->getAlnum('education')) {
            $queryBuilder
                ->andWhere('bp.education LIKE :education')
                ->setParameter('education', '%' . $request->query
                        ->getAlnum('education') . '%');
        }
        if ($request->query->getAlnum('languages')) {
            $queryBuilder
                ->andWhere('bp.languages LIKE :languages')
                ->setParameter('languages', '%' . $request->query
                        ->getAlnum('languages') . '%');
        }
        if ($request->query->getAlnum('salary')) {
            $queryBuilder
                ->andWhere('bp.salary <= :salary')
                ->setParameter('salary', $request->query
                        ->getAlnum('salary'));
        }
        if ($request->query->getAlnum('experience')) {
            $queryBuilder
                ->andWhere('bp.experience LIKE :experience')
                ->setParameter('experience', '%' . $request->query
                        ->getAlnum('experience') . '%');
        }

        $query = $queryBuilder
           // ->where('bp.user.mainNum = bp.id')
            ->getQuery();

        $count = $queryBuilder
            //->where('bp.user.mainNum = bp.id')
            ->select('Count(bp.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $this->addFlash('success', 'Pagal kriterijus radome '. $count . ' atitikmenis');

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
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_EMPLOYEE')")
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
            if($this->isGranted('ROLE_EMPLOYEE')){
                $tracking = new Tracking();
                $tracking->setUser($this->getUser());
                $tracking->setAction('Redagavo savo CV anketą');
                $tracking->setTime(new DateTime());
                $entityManager->persist($tracking);
                $entityManager->flush();
            }
            return $this->redirectToRoute('resume_index');
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/resume/{id}", name="show_resume", requirements={"id"="\d+"})
     * @Template("resume/show.html.twig")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_EMPLOYEE')")
     */
    public function showResumeAction($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        if($this->isGranted('ROLE_EMPLOYEE')){
            $tracking = new Tracking();
            $tracking->setUser($this->getUser());
            $tracking->setAction('Peržiūrėjo savo CV anketą');
            $tracking->setTime(new DateTime());
            $entityManager->persist($tracking);
            $entityManager->flush();
        }
        $resume = $this->getDoctrine()->getRepository(Resume::class)->find($id);
        return ['resume' => $resume];
    }

    /**
     * @Route("/download/{id}", name="download_resume", requirements={"id"="\d+"})
     * @Template("resume/show.html.twig")
     */
    public function downloadResumeAction($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $tracking = new Tracking();
        $tracking->setUser($this->getUser());
        $tracking->setAction('Parsisiuntė CV anketą');
        $tracking->setTime(new DateTime());
        $entityManager->persist($tracking);
        $entityManager->flush();
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
        $dompdf->setPaper('A4', 'portrait');

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
     * @IsGranted("ROLE_EMPLOYER")
     */
    public function showResumeForEmployerAction($id)
    {
        $resume = $this->getDoctrine()->getRepository(Resume::class)->find($id);
        if($this->getUser()->getCredits() >= 10){
            $entityManager = $this->getDoctrine()->getManager();
            $this->getUser()->setCredits($this->getUser()->getCredits()-10);
            $entityManager->flush();
            $entityManager = $this->getDoctrine()->getManager();
            if($this->isGranted('ROLE_EMPLOYER')){
                $tracking = new Tracking();
                $tracking->setUser($this->getUser());
                $tracking->setAction('Peržiūrėjo darbuotojo CV už 10 kreditų, kurio ID: '. $id);
                $tracking->setTime(new DateTime());
                $entityManager->persist($tracking);
                $entityManager->flush();
            }
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
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_EMPLOYEE')")
     */
    public function removeResume($id)
    {
        $resume = $this->getDoctrine()->getRepository(Resume::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($resume);
        $entityManager->flush();
        $this->addFlash('success', 'CV anketa ištrinta');
        if($this->isGranted('ROLE_EMPLOYEE')){
            $tracking = new Tracking();
            $tracking->setUser($this->getUser());
            $tracking->setAction('Ištrynė CV anketą');
            $tracking->setTime(new DateTime());
            $entityManager->persist($tracking);
            $entityManager->flush();
        }
        return $this->redirectToRoute('resume_index');
    }
}


