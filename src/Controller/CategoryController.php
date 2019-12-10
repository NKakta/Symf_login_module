<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryFormType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;


class CategoryController extends AbstractController
{
    private $repo;

    public function __construct(CategoryRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @Route("/categories", name="category_index")
     * @Method({"GET"})
     * @Template("category/index.html.twig")
     * @param Request $request
     * @return array
     */
    public function listCategories(Request $request)
    {
        $categories = $this->repo->findAll();

        $form = $this->createForm(CategoryFormType::class);

        $name = $request->query->get('name');

        $filtered = [];
        foreach ($categories as $category) {
            if ($category->getName() == $name) {
                array_push($filtered, $category);
            }
        }

        if (!$name) {
            $filtered = $categories;
        }

        return ['categories' => $filtered, 'form' => $form->createView()];
    }

    /**
     * @Route("/admin/category/create", name="create_category")
     * @Method({"GET", "POST"})
     * @Template("category/create.html.twig")
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     */
    public function createCategory(Request $request)
    {
        $category = new Category();
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();
            $this->addFlash('success', 'Category created');
            //Sends email to the user with login link
            return $this->redirectToRoute('category_index');
        }
        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Invalid data');
        }
        return ['form' => $form->createView()];
    }

    /**
     * @Route("/admin/category/edit/{id}", name="edit_category", requirements={"id"="\d+"})
     * @Method({"GET", "POST"})
     * @Template("category/edit.html.twig")
     */
    public function editCategory(Request $request, $id)
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->find($id);
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('category_index');
        }
        return ['form' => $form->createView()];
    }

    /**
     * @Route("/admin/category/{id}", name="show_category", requirements={"id"="\d+"})
     * @Template("category/show.html.twig")
     */
    public function showCategory($id)
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->find($id);
        return ['category' => $category];
    }

    /**
     * @Route("admin/category/remove/{id}", name="remove_category", requirements={"id"="\d+"})
     */
    public function removeCategory($id)
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($category);
        $entityManager->flush();
        $this->addFlash('success', 'Category has been removed');
        return $this->redirectToRoute('category_index');
    }
}


