<?php

namespace App\Repository;

use App\Entity\Chapter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Chapter>
 */
class ChapterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Chapter::class);
    }

    //    /**
    //     * @return Chapter[] Returns an array of Chapter objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }
    public function findAllChaptersByTutorialId($tutorialId): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.tutorials = :tutorialsId')
            ->setParameter('tutorialsId', $tutorialId)
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function countChaptersByTutorialId($tutorialId): int
    {
        return $this->createQueryBuilder('c')
            ->select('count(c.id)')
            ->andWhere('c.tutorials = :tutorialsId')
            ->setParameter('tutorialsId', $tutorialId)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findChapterById($chapterId): ?Chapter
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.id = :chapterId')
            ->setParameter('chapterId', $chapterId)
            ->getQuery()
            ->getOneOrNullResult();
    }


    //    public function findOneBySomeField($value): ?Chapter
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
