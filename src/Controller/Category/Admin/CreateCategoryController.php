<?php
declare(strict_types=1);

namespace App\Controller\Category\Admin;

use App\Entity\Category;
use App\Form\Category\CategoryFormType;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;


class CreateCategoryController extends AbstractController
{
    private $repo;

    public function __construct(CategoryRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @Route("/admin/category/create", name="create_category")
     * @Method({"GET", "POST"})
     * @Template("category/admin/create.html.twig")
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     */
    public function createCategoryAction(Request $request)
    {
        $category = new Category();
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $category->setCreatedAt(new \DateTime());
            $category->setUpdatedAt(new \DateTime());
            $entityManager->persist($category);
            $entityManager->flush();

            $this->addFlash('success', 'Category  created');

            //Sends email to the user with login link
            return $this->redirectToRoute('admin_category_index');
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Invalid data');
        }

        return ['form' => $form->createView()];
    }
}


