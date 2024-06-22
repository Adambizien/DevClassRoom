<?php

namespace App\Repository;

use App\Entity\Tutorials;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tutorials>
 */
class TutorialsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tutorials::class);
    }

//    /**
//     * @return Tutorials[] Returns an array of Tutorials objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }
    public function findTutorialById($tutorialId): ?Tutorials
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.id = :tutorialId')
            ->setParameter('tutorialId', $tutorialId)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findAllTutorialsWithAuthorEmail($email): array
    {
        return $this->createQueryBuilder('t')
            ->where('t.author = :email')
            ->setParameter('email', $email)
            ->orderBy('t.id', 'ASC')
            ->getQuery()
            ->getResult();
    }
//    public function findOneBySomeField($value): ?Tutorials
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
