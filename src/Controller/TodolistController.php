<?php

namespace App\Controller;

use App\Entity\Todolist;
use App\Entity\User;
use App\Form\TodolistFormType;
use App\Service\TodolistService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ContactController
 * @Route("/todolist")
 */
class TodolistController extends AbstractController
{
    /**
     * @var TodolistService
     */
    private $todolistService;

    /**
     * TodolistController constructor.
     * @param \App\Service\TodolistService $todolistService
     */
    public function __construct(TodolistService $todolistService)
    {
        $this->todolistService = $todolistService;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @Route(
     *     "/",
     *     methods={"GET"},
     *     name="todolist_index",
     * )
     */
    public function index(Request $request): Response
    {
        return $this->render(
            'todolist/index.html.twig',
            ['pagination' => $this->todolistService->createPaginatedList($request->query->getInt('page', 1))]
        );
    }

    /**
     * Create action.
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/create",
     *     methods={"GET", "POST"},
     *     name="todolist_create",
     * )
     */
    public function create(Request $request): Response
    {
        $todolist = new Todolist();
        $form = $this->createForm(TodolistFormType::class, $todolist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->todolistService->save($todolist);

            $this->addFlash('success', 'message_added_successfully');

            return $this->redirectToRoute('todolist_index');
        }

        return $this->render(
            'todolist/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request  HTTP request
     * @param \App\Entity\Todolist                      $todolist Todolist entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/edit",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "\d+"},
     *     name="todolist_edit",
     * )
     */
    public function edit(Request $request, Todolist $todolist): Response
    {
        $form = $this->createForm(TodolistFormType::class, $todolist, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->todolistService->save($todolist);

            $this->addFlash('success', 'message_updated_successfully');

            return $this->redirectToRoute('todolist_index');
        }

        return $this->render(
            'todolist/edit.html.twig',
            [
                'form' => $form->createView(),
                'todolist' => $todolist,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request  HTTP request
     * @param \App\Entity\Todolist                      $todolist Todolist entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/delete",
     *     methods={"GET", "DELETE"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="todolist_delete",
     * )
     */
    public function delete(Request $request, Todolist $todolist): Response
    {
        $form = $this->createForm(FormType::class, $todolist, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->todolistService->delete($todolist);
            $this->addFlash('success', 'message_deleted_successfully');

            return $this->redirectToRoute('todolist_index');
        }

        return $this->render(
            'todolist/delete.html.twig',
            [
                'form' => $form->createView(),
                'todolist' => $todolist,
            ]
        );
    }
}
