<?php

namespace App\Controller;

use App\Entity\Department;
use App\Entity\Vacation;
use App\Entity\VacationRequest;
use App\Form\VacationRequestFormType;
use App\Repository\VacationRequestRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;


class VacationRequestController extends AbstractController
{
    private $repo;

    public function __construct(VacationRequestRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @Route("/admin/vacation-requests", name="vacation_request_index")
     * @Method({"GET"})
     * @Template("vacation-request/index.html.twig")
     * @return array
     */
    public function listVacationRequests()
    {
        $vacationRequests = $this->repo->findAll();

        return ['vacationRequests' => $vacationRequests];
    }

    /**
     * @Route("/admin/vacation-request/create", name="create_vacation_request")
     * @Method({"GET", "POST"})
     * @Template("vacation-request/create.html.twig")
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createVacationRequestAction(Request $request)
    {
        /* @var Department $department */
        $department = $this->getUser()->getDepartment();
        $users = $department->getUsers();
        $freeUsers = [];

        foreach ($users as $user) {
            if (!empty($user->getVacationRequests())) {
                $freeUsers[] = $user;
            }
        }

        if (count($freeUsers) <= 1) {
            $this->addFlash('error', 'Whole department can not go on vacation');
            return $this->redirectToRoute('vacation_request_index');
        }

        $vacationRequest = new VacationRequest();
        $form = $this->createForm(VacationRequestFormType::class, $vacationRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $vacationRequest->setUser($this->getUser());
            $entityManager->persist($vacationRequest);
            $entityManager->flush();
            $this->addFlash('success', 'VacationRequest created');
            //Sends email to the user with login link
            return $this->redirectToRoute('vacation_request_index');
        }
        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Invalid data');
        }
        return ['form' => $form->createView()];
    }

    /**
     * @Route("/admin/vacation-request/edit/{id}", name="edit_vacation_request", requirements={"id"="\d+"})
     * @Method({"GET", "POST"})
     * @Template("vacation-request/edit.html.twig")
     */
    public function editVacationRequestAction(Request $request, $id)
    {
        $vacationRequest = $this->getDoctrine()->getRepository(VacationRequest::class)->find($id);
        $form = $this->createForm(VacationRequestFormType::class, $vacationRequest);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('vacation_request_index');
        }
        return ['form' => $form->createView()];
    }

    /**
     * @Route("/admin/vacation-request/{id}", name="show_vacation_request", requirements={"id"="\d+"})
     * @Template("vacation-request/show.html.twig")
     */
    public function showVacationRequestAction($id)
    {
        $vacationRequest = $this->getDoctrine()->getRepository(VacationRequest::class)->find($id);
        return ['vacationRequest' => $vacationRequest];
    }

    /**
     * @Route("admin/vacation-request/remove/{id}", name="remove_vacation_request", requirements={"id"="\d+"})
     */
    public function removeVacationRequest($id)
    {
        $vacationRequest = $this->getDoctrine()->getRepository(VacationRequest::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($vacationRequest);
        $entityManager->flush();
        $this->addFlash('success', 'VacationRequest has been removed');
        return $this->redirectToRoute('vacation_request_index');
    }

    /**
     * @Route("admin/vacation-request/accept/{id}", name="accept_vacation_request", requirements={"id"="\d+"})
     * @IsGranted("ROLE_SUPERVISOR")
     */
    public function acceptVacationRequest($id)
    {
        /* @var VacationRequest $vacationRequest */
        $vacationRequest = $this->getDoctrine()->getRepository(VacationRequest::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($vacationRequest);

        $vacation = new Vacation();
        $vacation->setUser($vacationRequest->getUser());
        $vacation->setDateFrom($vacationRequest->getDateFrom());
        $vacation->setDateTo($vacationRequest->getDateTo());
        $vacation->setName($vacationRequest->getUser()->getUsername() . ' vacation');
        $vacation->setDescription('default');

        $entityManager->persist($vacation);

        $entityManager->flush();
        $this->addFlash('success', 'VacationRequest has been accepted');
        return $this->redirectToRoute('vacation_request_index');
    }
}


