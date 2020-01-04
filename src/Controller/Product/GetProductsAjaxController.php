<?php
declare(strict_types=1);

namespace App\Controller\Product;

use App\Entity\Category;
use App\Enum\Regions;
use App\Repository\AccountRepository;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;


class GetProductsAjaxController extends AbstractController
{

    private $repo;
    /**
     * @var CategoryRepository
     */
    private $categoryRepo;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(
        ProductRepository $repo,
        CategoryRepository $categoryRepo,
        SerializerInterface $serializer
    ) {
        $this->repo = $repo;
        $this->categoryRepo = $categoryRepo;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/ajax/products", name="products_ajax")
     *
     * @Method({"GET"})
     * @param Request $request
     * @return Response
     */
    public function listProducts(Request $request)
    {
        $products = $this->repo->findAll();
        $region = $request->query->get('region');
        $result = $this->render(
            'product/ajax/products.html.twig',
            [
                'products' => $products,
                'region' => $region
            ]
        );
        return $result;
    }

}


