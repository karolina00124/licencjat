<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventFormFilterType;
use App\Form\EventFormType;
use App\Service\TodolistService;
use App\Service\EventService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class EventController
 * @Route("/event")
 */
class EventController extends AbstractController
{
    /** @var \App\Service\EventService */
    private $eventService;
    /** @var \App\Service\TodolistService */
    private $todolistService;

    /**
     * EventController constructor.
     * @param \App\Service\EventService    $eventService
     * @param \App\Service\TodolistService $todolistService
     */
    public function __construct(EventService $eventService, TodolistService $todolistService)
    {
        $this->eventService = $eventService;
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
     *     name="event_index",
     * )
     */
   public function index(Request $request): Response
    {
        $filters = [];
        $filters['todolist_id'] = $request->query->has('todolist') ? (int) $request->query->get('todolist') : null;

	$todolist = null;

	if ($filters['todolist_id'] != null) {
	    $todolist = $this->todolistService->getById($filters['todolist_id']);
	}


        return $this->render('event/index.html.twig', [
            'pagination' => $this->eventService->createPaginatedList($filters, $request->query->getInt('page', 1)),
	    'todolist' => $todolist
        ]);

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
     *     name="event_create",
     * )
     */
    public function create(Request $request): Response
    {
        $event = new Event();
        $form = $this->createForm(EventFormType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $event->setUser($this->getUser());
            $this->eventService->save($event);

            $this->addFlash('success', 'message_added_successfully');

            return $this->redirectToRoute('event_index');
        }

        return $this->render(
            'event/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     * @param \App\Entity\Todolist                      $event   Event entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/edit",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="event_edit",
     * )
     */
    public function edit(Request $request, Event $event): Response
    {
        $form = $this->createForm(EventFormType::class, $event, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $event->setUser($this->getUser());
            $this->eventService->save($event);

            $this->addFlash('success', 'message_updated_successfully');

            return $this->redirectToRoute('event_index');
        }

        return $this->render(
            'event/edit.html.twig',
            [
                'form' => $form->createView(),
                'event' => $event,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     * @param \App\Entity\Todolist                      $event   Event entity
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
     *     name="event_delete",
     * )
     */
    public function delete(Request $request, Event $event): Response
    {
        $form = $this->createForm(FormType::class, $event, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->eventService->delete($event);
            $this->addFlash('success', 'message_deleted_successfully');

            return $this->redirectToRoute('event_index');
        }

        return $this->render(
            'event/delete.html.twig',
            [
                'form' => $form->createView(),
                'event' => $event,
            ]
        );
    }

    /**
     * @Route("/{id}/change_done",
     *     requirements={"id": "[1-9]\d*"},
     *     name="event_change_done"
     * )
     */
    public function changeDone(Event $event): Response
    {
        $this->eventService->changeDone($event);

	return $this->redirectToRoute('event_index', ['todolist' => $event->getTodolist()->getId()]);
    }
}
