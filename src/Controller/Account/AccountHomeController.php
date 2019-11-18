<?php
declare(strict_types=1);

namespace App\Controller\Account;

use App\Entity\Account;
use App\Enum\Regions;
use App\Form\AccountFormType;
use App\Repository\AccountRepository;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


class AccountHomeController extends AbstractController
{
    private $repo;

    private $categoryRepo;

    private $productRepo;

    public function __construct(
        AccountRepository $repo,
        CategoryRepository $categoryRepo,
        ProductRepository $productRepo
    ) {
        $this->repo = $repo;
        $this->categoryRepo = $categoryRepo;
        $this->productRepo = $productRepo;
    }

    /**
     * @Route("/", name="account_index")
     * @Method({"GET"})
     * @Template("account/index.html.twig")
     * @return array
     */
    public function listResumes()
    {
        $accounts = $this->repo->findAll();
        $categories = $this->categoryRepo->findAll();
        $products = $this->productRepo->findAll();
        $regions = Regions::getAll();

        return [
            'categories' => $categories,
            'accounts' => $accounts,
            'products' => $products,
            'regions' => $regions
        ];
    }
}


