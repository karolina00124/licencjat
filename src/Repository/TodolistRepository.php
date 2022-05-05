<?php

namespace App\Repository;

use App\Entity\Todolist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class TodolistRepository
 */
class TodolistRepository extends ServiceEntityRepository
{
    /**
     * TodolistRepository constructor.
     * @param \Doctrine\Persistence\ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Todolist::class);
    }

    /**
     * Query all records.
     *
     * @return \Doctrine\ORM\QueryBuilder Query builder
     */
    public function queryAll(): QueryBuilder
    {
        return $this
            ->createQueryBuilder('todolist')
            ->orderBy('todolist.name', 'ASC');
    }

    /**
     * Save record.
     * @param \App\Entity\Todolist $todolist Todolist entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Todolist $todolist): void
    {
        $this->_em->persist($todolist);
        $this->_em->flush();
    }

    /**
     * Delete record.
     * @param \App\Entity\Todolist $todolist Todolist entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Todolist $todolist): void
    {
        $this->_em->remove($todolist);
        $this->_em->flush();
    }

    public function getById(int $id): Todolist
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.id = :id')
	    ->setParameter('id', $id)
	    ->getQuery()
	    ->getOneOrNullResult()
	;
    }
}
