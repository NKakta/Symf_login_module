<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\User;
use App\Event\UserRegisteredEvent;
use App\Form\ItemFormType;
use App\Form\UserFormType;
use App\Repository\PictureRepository;
use App\Repository\ItemRepository;
use DateTime;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;


class ItemController extends AbstractController
{
    private $repo;

    public function __construct(ItemRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @Route("items", name="item_index")
     * @Method({"GET"})
     * @Template("item/index.html.twig")
     * @return array
     */
    public function listItems()
    {
        $items = $this->repo->findAll();

        return ['items' => $items];
    }

    /**
     * @Route("item/create", name="create_item")
     * @Method({"GET", "POST"})
     * @Template("item/create.html.twig")
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createItemAction(Request $request)
    {
        $item = new Item();
        $form = $this->createForm(ItemFormType::class, $item);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $item->setDateFrom(new DateTime());
            $item->setDateTo(new DateTime());
            $item->setSupplier($this->getUser());
            $entityManager->persist($item);
            $entityManager->flush();
            $this->addFlash('success', 'Item created');

            return $this->redirectToRoute('item_index');
        }
        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Invalid data');
        }
        return ['form' => $form->createView()];
    }

    /**
     * @Route("item/edit/{id}", name="edit_item", requirements={"id"="\d+"})
     * @Method({"GET", "POST"})
     * @Template("item/edit.html.twig")
     */
    public function editItemAction(Request $request, $id)
    {
        $item = $this->getDoctrine()->getRepository(Item::class)->find($id);
        $form = $this->createForm(ItemFormType::class, $item);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('item_index');
        }
        return ['form' => $form->createView()];
    }
    /**
     * @Route("item/{id}", name="show_item", requirements={"id"="\d+"})
     * @Template("item/show.html.twig")
     */
    public function showItemAction($id)
    {
        $item = $this->getDoctrine()->getRepository(Item::class)->find($id);
        return ['item' => $item];
    }
    /**
     * @Route("item/remove/{id}", name="remove_item", requirements={"id"="\d+"})
     */
    public function removeItem($id)
    {
        $item = $this->getDoctrine()->getRepository(Item::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($item);
        $entityManager->flush();
        $this->addFlash('success', 'Item has been removed');
        return $this->redirectToRoute('item_index');
    }

    /**
     * @Route("statistics", name="index_statistics", requirements={"id"="\d+"})
     * @Template("item/statistics.html.twig")
     * @IsGranted("ROLE_DIRECTOR")
     */
    public function showStatistics()
    {
        $items = $this->getDoctrine()->getRepository(Item::class)->findAll();
        return ['itemCount' => count($items)];
    }
}


