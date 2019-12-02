<?php

namespace App\Controller;

use App\Entity\User;
use App\Event\UserRegisteredEvent;
use App\Form\UserFormType;
use App\Repository\PictureRepository;
use App\Repository\VacationRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;


class VacationController extends AbstractController
{
    private $repo;

    public function __construct(VacationRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @Route("/vacations", name="vacation_index")
     * @Method({"GET"})
     * @Template("vacation/index.html.twig")
     * @return array
     */
    public function listVacations()
    {
        $pictures = $this->repo->findAll();

        return ['vacations' => $pictures];
    }
}


