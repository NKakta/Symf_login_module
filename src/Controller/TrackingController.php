<?php

namespace App\Controller;

use App\Entity\Tracking;
use App\Repository\TrackingRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TrackingController extends AbstractController
{
    private $repo;

    public function __construct(TrackingRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @Route("/admin/tracking/{id}", name="tracking_index", requirements={"id"="\d+"})
     * @Method({"GET"})
     * @Template("admin/tracking/index.html.twig")
     * @return array
     */
    public function listTracking(Request $request, $id)
    {
        $tracking = $this->repo->findById($id);
        return ['tracking' => $tracking];
    }
}
