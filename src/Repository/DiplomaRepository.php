<?php

namespace App\Repository;

use App\Entity\Diploma;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Diploma|null find($id, $lockMode = null, $lockVersion = null)
 * @method Diploma|null findOneBy(array $criteria, array $orderBy = null)
 * @method Diploma[]    findAll()
 * @method Diploma[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DiplomaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Diploma::class);
    }

    /**
     * @return Diploma[] Returns an array of Diploma objects
     */

    public function getCapturedDiplomas($child)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.child = :val')
            ->andWhere('d.capturedAt IS NOT NULL')
            ->setParameter('val', $child)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Diploma[] Returns an array of Diploma objects
     */

    public function getUnCapturedDiplomas($child)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.child = :val')
            ->andWhere('d.capturedAt IS NULL')
            ->setParameter('val', $child)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Diploma[] Returns an array of Diploma objects
     */

    public function getDiplomaWithTasks($diploma)
    {
        return $this->createQueryBuilder('d')
            ->leftJoin('d.tasks', 't')
            ->andWhere('d.id = :val')
            ->setParameter('val', $diploma->getId())
            ->getQuery()
            ->getResult();
    }


    // /**
    //  * @return Diploma[] Returns an array of Diploma objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Diploma
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
