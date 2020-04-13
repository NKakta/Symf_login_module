<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\Client\AbstractClientController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class HomeController extends AbstractClientController
{
    /**
     * @Route("/", name="app_homepage")
     * @Method({"GET", "POST"})
     * @Template("home.html.twig")
     */
    public function showHomepageAction() {
        return ['cart' => $this->getCart()];
    }
}
