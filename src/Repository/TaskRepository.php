<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TaskRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry) {
        parent:: __construct($registry, Task::class);
    }

    public function findByCompleted(bool $completed) : array { 
        return $this
        ->createQueryBuilder('t') 
        ->andWhere('t.completed = : completed') 
        ->setParameter('completed', $completed)
        ->orderBy('t.createdAt', 'DESC')
        ->getQuery()
        ->getResult();
    }

    public function save (Task $task) : void {
        $this->getEntityManager()->persist($task);
        $this->getEntityManager()->flush();
    }

    public function delete (Task $task) : void {
        $this->getEntityManager()->remove($task);
        $this->getEntityManager()->flush();
    }

}