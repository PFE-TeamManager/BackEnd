<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function countTasksWithProjects(){
        return $this->createQueryBuilder('t')
                    ->select(" COUNT(t.id) as countTask")
                    ->leftJoin('t.IdProject', 'p')
                    ->addSelect('p.id as idProject,p.projectName,p.enabled as projectenabled')
                    ->orderBy('t.createdAt', 'DESC')
                    ->groupBy('p.projectName')
                    ->getQuery()
                    ->getResult();
    }

    public function countTasksWithStateToDo(){
        return $this->createQueryBuilder('t')
                    ->select(" COUNT(t.id) as countTaskToDo")
                    ->where('t.ToDo = 1')
                    ->getQuery()
                    ->getResult();
    }

    public function countTasksWithStateDoing(){
        return $this->createQueryBuilder('t')
                    ->select(" COUNT(t.id) as countTaskDoing")
                    ->where('t.doing = 1')
                    ->getQuery()
                    ->getResult();
    }

    public function countTasksWithStateDone(){
        return $this->createQueryBuilder('t')
                    ->select(" COUNT(t.id) as countTaskDone")
                    ->where('t.done = 1')
                    ->getQuery()
                    ->getResult();
    }

    // /**
    //  * @return Task[] Returns an array of Task objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Task
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
