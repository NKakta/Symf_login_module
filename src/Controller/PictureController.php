<?php

namespace App\Controller;

use App\Entity\User;
use App\Event\UserRegisteredEvent;
use App\Form\UserFormType;
use App\Repository\PictureRepository;
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


class PictureController extends AbstractController
{
    private $repo;

    public function __construct(PictureRepository $repo)
    {
        $this->repo = $repo;
    }


    /**
     * @Route("/admin/pictures", name="picture_index")
     * @Method({"GET"})
     * @Template("picture/index.html.twig")
     * @param Request $request
     * @return array
     */
    public function listPictures(Request $request)
    {
        $pictures = $this->repo->findAll();


        return ['pictures' => $pictures];
    }
}


