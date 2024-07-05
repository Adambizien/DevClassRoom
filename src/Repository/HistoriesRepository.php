<?php

namespace App\Repository;

use App\Entity\Histories;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Histories>
 */
class HistoriesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Histories::class);
    }

    public function getHistoriesByUserIdAndTutorialId($userId, $tutorialId): array
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.users = :userId')
            ->andWhere('h.tutorials = :tutorialId')
            ->setParameter('userId', $userId)
            ->setParameter('tutorialId', $tutorialId)
            ->orderBy('h.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function getAllHistoriesByUserIdWithTutorialStatusOn($userId): array
    {
        return $this->createQueryBuilder('h')
            ->join('h.tutorials', 't') 
            ->where('h.users = :userId')
            ->andWhere('t.status = :status')
            ->setParameter('userId', $userId)
            ->setParameter('status', 'on')
            ->orderBy('h.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function removeHistoriesByUserId($userId): void
    {
        $this->createQueryBuilder('h')
            ->delete()
            ->where('h.users = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->execute();
    }

    //    /**
    //     * @return Histories[] Returns an array of Histories objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('h')
    //            ->andWhere('h.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('h.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Histories
    //    {
    //        return $this->createQueryBuilder('h')
    //            ->andWhere('h.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
