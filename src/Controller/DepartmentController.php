<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Department;
use App\Form\DepartmentFormType;
use App\Repository\DepartmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class DepartmentController extends AbstractController
{
    private $repo;

    public function __construct(DepartmentRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @Route("/admin/departments", name="department_index")
     * @Method({"GET"})
     * @Template("department/index.html.twig")
     * @return array
     */
    public function listDepartments()
    {
        $departments = $this->repo->findAll();

        return ['departments' => $departments];
    }

    /**
     * @Route("/admin/department/create", name="create_department")
     * @Method({"GET", "POST"})
     * @Template("department/create.html.twig")
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createDepartmentAction(Request $request)
    {
        $department = new Department();
        $department->setDateTo(new \DateTime());
        $department->setDateFrom(new \DateTime());
        $form = $this->createForm(DepartmentFormType::class, $department);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($department);
            $entityManager->flush();
            $this->addFlash('success', 'Department created');
            //Sends email to the user with login link
            return $this->redirectToRoute('department_index');
        }
        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Invalid data');
        }
        return ['form' => $form->createView()];
    }

    /**
     * @Route("/admin/department/edit/{id}", name="edit_department", requirements={"id"="\d+"})
     * @Method({"GET", "POST"})
     * @Template("department/edit.html.twig")
     */
    public function editDepartmentAction(Request $request, $id)
    {
        $department = $this->getDoctrine()->getRepository(Department::class)->find($id);
        $form = $this->createForm(DepartmentFormType::class, $department);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $department->setDateTo(new \DateTime());
            $department->setDateFrom(new \DateTime());

            $entityManager->flush();
            return $this->redirectToRoute('department_index');
        }
        return ['form' => $form->createView()];
    }

    /**
     * @Route("/admin/department/{id}", name="show_department", requirements={"id"="\d+"})
     * @Template("department/show.html.twig")
     */
    public function showDepartmentAction($id)
    {
        $department = $this->getDoctrine()->getRepository(Department::class)->find($id);
        return ['department' => $department];
    }

    /**
     * @Route("admin/department/remove/{id}", name="remove_department", requirements={"id"="\d+"})
     */
    public function removeDepartment($id)
    {
        $department = $this->getDoctrine()->getRepository(Department::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($department);
        $entityManager->flush();
        $this->addFlash('success', 'Department has been removed');
        return $this->redirectToRoute('department_index');
    }
}


