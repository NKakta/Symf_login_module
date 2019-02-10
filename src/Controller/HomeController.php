<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_homepage")
     * @Method({"GET", "POST"})
     */
    public function index() {
        //return new Response('<html><div>Hello</div></html>');

        return $this->render('home.html.twig');
    }
}