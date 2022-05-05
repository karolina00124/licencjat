<?php

namespace App\Service;

use App\Entity\Todolist;
use App\Repository\TodolistRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class TodolistService
 */
class TodolistService
{
    const PAGINATOR_ITEMS_PER_PAGE = 10;

    /** @var TodolistRepository */
    private $todolistRepository;
    /** @var PaginatorInterface */
    private $paginator;

    /**
     * TodolistService constructor.
     * @param \App\Repository\TodolistRepository      $todolistRepository
     * @param \Knp\Component\Pager\PaginatorInterface $paginator
     */
    public function __construct(TodolistRepository $todolistRepository, PaginatorInterface $paginator)
    {
        $this->todolistRepository = $todolistRepository;
        $this->paginator = $paginator;
    }

    /**
     * @param int $page
     *
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    public function createPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->todolistRepository->queryAll(),
            $page,
            self::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Zapisuje do bazy
     * @param \App\Entity\Todolist $todolist
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Todolist $todolist)
    {
        $this->todolistRepository->save($todolist);
    }

    /**
     * Usuwa z bazy
     * @param \App\Entity\Todolist $todolist
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Todolist $todolist)
    {
        $this->todolistRepository->delete($todolist);
    }

    public function getById(int $id)
    {
        return $this->todolistRepository->getById($id);
    }
}
