<?php
declare(strict_types=1);

namespace App\Controller\Category\Admin;

use App\Form\Category\CategoryFormType;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class EditCategoryController extends AbstractController
{
    private $repo;

    public function __construct(CategoryRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @Route("/admin/category/edit/{id}", name="edit_category", requirements={"id": "[a-zA-Z0-9\-]{36,}"})
     * @Method({"GET", "POST"})
     * @Template("category/admin/edit.html.twig")
     * @param Request $request
     * @param $id
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editCategoryAction(Request $request, $id)
    {
        $category = $this->repo->find($id);
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('admin_category_index');
        }

        return ['form' => $form->createView()];
    }
}


