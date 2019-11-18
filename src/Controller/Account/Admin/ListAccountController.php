<?php
declare(strict_types=1);

namespace App\Controller\Account\Admin;

use App\Form\Account\SearchAccountFormType;
use App\Model\AccountFilterModel;
use App\Repository\AccountRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;


class ListAccountController extends AbstractController
{
    private $repo;

    public function __construct(AccountRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @Route("/admin/accounts", name="admin_account_index")
     * @Method({"GET"})
     * @Template("account/admin/index.html.twig")
     * @return array
     */
    public function listAccounts(Request $request, PaginatorInterface $paginator)
    {
        $filter = new AccountFilterModel();
        $form = $this->createForm(SearchAccountFormType::class, $filter, ['method' => 'GET']);
        $form->handleRequest($request);

        $result = $paginator->paginate(
            $this->repo->getAllQuery($filter),
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 15)
        );

        return ['accounts' => $result, 'form' => $form->createView()];
    }


//    public function userListAction(Request $request, PaginatorInterface $paginator)
//    {
//        $em = $this->getDoctrine()->getManager();
//
//        //$users = $em->getRepository(User::class)->findAll();
//        $form = $this->createForm(SearchEmailFormType::class);
//        $queryBuilder = $em->getRepository('App\Entity\User')->createQueryBuilder('bp');
//
//        if ($request->query->getAlnum('email')) {
//            $queryBuilder
//                ->where('bp.email LIKE :email')
//                ->setParameter('email', '%' . $request->query
//                        ->getAlnum('email') . '%');
//        }
//
//        $query = $queryBuilder->getQuery();
//
//        $result = $paginator->paginate(
//            $query,
//            $request->query->getInt('page', 1),
//            $request->query->getInt('limit', 6)
//        );
//
//        return ['users' => $result, 'form' => $form->createView()];
//    }


//    /**
//     * @Route("/admin/account/create", name="create_resume")
//     * @Method({"GET", "POST"})
//     * @Template("account/create.html.twig")
//     * @param Request $request
//     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
//     */
//    public function createAccountAction(Request $request)
//    {
//
//        $resume = new Account();
//        $form = $this->createForm(AccountFormType::class, $resume);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $entityManager = $this->getDoctrine()->getManager();
//
//            $resume->setCreatedAt(new DateTime());
//            $resume->setUpdatedAt(new DateTime());
//            $resume->setUser($this->getUser());
//            $entityManager->persist($resume);
//            $entityManager->flush();
//
//            $this->addFlash('success', 'Account created');
//
//            //Sends email to the user with login link
//            return $this->redirectToRoute('resume_index');
//        }
//
//        if ($form->isSubmitted() && !$form->isValid()) {
//            $this->addFlash('error', 'Invalid data');
//        }
//
//        return ['form' => $form->createView()];
//    }
//
//    /**
//     * @Route("/admin/account/edit/{id}", name="edit_resume", requirements={"id"="\d+"})
//     * @Method({"GET", "POST"})
//     * @Template("account/edit.html.twig")
//     */
//    public function editAccountAction(Request $request, $id)
//    {
//        $resume = $this->getDoctrine()->getRepository(Account::class)->find($id);
//        $form = $this->createForm(AccountFormType::class, $resume);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $entityManager = $this->getDoctrine()->getManager();
//            $entityManager->flush();
//            return $this->redirectToRoute('resume_index');
//        }
//
//        return ['form' => $form->createView()];
//    }
//
//    /**
//     * @Route("/admin/account/{id}", name="show_resume", requirements={"id"="\d+"})
//     * @Template("account/show.html.twig")
//     */
//    public function showAccountAction($id)
//    {
//        $resume = $this->getDoctrine()->getRepository(Account::class)->find($id);
//        return ['account' => $resume];
//    }
//
//    /**
//     * @Route("account/remove/{id}", name="remove_resume", requirements={"id"="\d+"})
//     */
//    public function removeAccount($id)
//    {
//        $resume = $this->getDoctrine()->getRepository(Account::class)->find($id);
//        $entityManager = $this->getDoctrine()->getManager();
//        $entityManager->remove($resume);
//        $entityManager->flush();
//        $this->addFlash('success', 'Account has been removed');
//        return $this->redirectToRoute('resume_index');
//    }
}


