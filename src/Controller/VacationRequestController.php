<?php

namespace App\Controller;

use App\Repository\VacationRequestRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


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
     * @Template("vacationRequest/index.html.twig")
     * @return array
     */
    public function listVacationRequests()
    {
        $pictures = $this->repo->findAll();

        return ['vacationRequests' => $pictures];
    }
}


