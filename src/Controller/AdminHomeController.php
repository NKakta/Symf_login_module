<?php
declare(strict_types=1);

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class AdminHomeController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_home")
     * @Method({"GET"})
     * @Template("admin/home.html.twig")
     */
    public function showHomepageAction() {
    }
}
