<?php
declare(strict_types=1);

namespace App\Controller\Account;

use App\Enum\Regions;
use App\Repository\AccountRepository;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


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
     * @param Request $request
     * @return array
     */
    public function listAccounts(Request $request)
    {
        $accounts = $this->repo->findAll();
        $categories = $this->categoryRepo->findAll();
        $products = $this->productRepo->findAll();
        $regions = Regions::getAll();
        $showModal = false;

        if ($request->query->has('thankyou')) {
            $showModal = true;
        }

        $getProductsAjaxUrl = $this->generateUrl(
            'products_ajax',
            [],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        return [
            'categories' => $categories,
            'accounts' => $accounts,
            'products' => $products,
            'regions' => $regions,
            'show_modal' => $showModal,
            'getProductsAjaxUrl' => $getProductsAjaxUrl
        ];
    }
}


