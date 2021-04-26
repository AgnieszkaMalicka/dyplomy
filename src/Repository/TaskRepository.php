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

    /**
     * @return TASK[] Returns an array of Task objects
     */

    public function getCountUncapturedTasksInDiploma($diploma)
    {
        // $qb = $em->createQueryBuilder();

        // $result = $qb->select('COUNT(u)')
        //              ->from('UserBundle:User' , 'u')
        //              ->leftJoin('u.UserGroup','g')
        //              ->where('g.GroupId = :id')
        //              ->andWhere('g.UserId = :null')
        //              ->setParameter('id', 70)
        //              ->setParameter('null', null)
        //              ->getQuery()
        //              ->getOneOrNullResult();

        return $this->createQueryBuilder('t')
            ->andWhere('t.diploma = :val')
            ->andWhere('t.madeAt IS NULL')
            ->setParameter('val', $diploma->getId())
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
