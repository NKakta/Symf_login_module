<?php
declare(strict_types=1);

namespace App\Controller\Category\Admin;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


class DeleteCategoryController extends AbstractController
{
    private $repo;

    public function __construct(CategoryRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @Route("category/remove/{id}", name="delete_category", requirements={"id": "[a-zA-Z0-9\-]{36,}"})
     * @param string $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeCategory(string $id)
    {
        $category = $this->repo->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($category);
        $entityManager->flush();
        $this->addFlash('success', 'Category has been removed');
        return $this->redirectToRoute('admin_category_index');
    }
}


