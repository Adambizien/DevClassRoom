<?php

namespace App\Repository;

use App\Entity\Content;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Content>
 */
class ContentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Content::class);
    }

//    /**
//     * @return Content[] Returns an array of Content objects
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


    public function countContentByChapterId($chapterId): int
    {
        return $this->createQueryBuilder('c')
            ->select('count(c.id)')
            ->andWhere('c.chapter = :chapterId')
            ->setParameter('chapterId', $chapterId)
            ->getQuery()
            ->getSingleScalarResult();
    }
    public function findAllContentByTutorialId($tutorialId): array
    {
        return $this->createQueryBuilder('c')
            ->join('c.chapter', 'ch') // Jointure avec la relation Many-to-One vers Chapter
            ->join('ch.tutorials', 't') // Jointure avec la relation Many-to-One vers Tutorial
            ->andWhere('t.id = :tutorialId')
            ->setParameter('tutorialId', $tutorialId)
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function allContentByChapterId($chapterId): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.chapter = :chapterId')
            ->setParameter('chapterId', $chapterId)
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findContentById($contentId): ?Content
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.id = :contentId')
            ->setParameter('contentId', $contentId)
            ->getQuery()
            ->getOneOrNullResult();
    }


//    public function findOneBySomeField($value): ?Content
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
