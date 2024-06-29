<?php

namespace App\Repository;

use App\Entity\Tutorials;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Common\Collections\ArrayCollection;


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

    public function findAllTutorialsWithStastusOn(): array
    {
        return $this->createQueryBuilder('t')
            ->where('t.status = :status')
            ->setParameter('status', 'on')
            ->orderBy('t.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    // Recherche par nom de formation et/ou catégories

    public function findBySearchCriteria(?string $searchTerm, ArrayCollection $categories): array
    {
        $qb = $this->createQueryBuilder('t');

        if ($searchTerm) {
            $qb->andWhere('t.title LIKE :searchTerm')
            ->setParameter('searchTerm', '%' . $searchTerm . '%');
        }

        $categoryIds = [];
        foreach ($categories as $category) {
            $categoryIds[] = $category->getId();
        }

        if (!empty($categoryIds)) {
            $qb->leftJoin('t.categories', 'c')
            ->groupBy('t.id')
            ->having('COUNT(c.id) = :numCategories')
            ->andWhere('c.id IN (:categoryIds)')
            ->setParameter('categoryIds', $categoryIds)
            ->setParameter('numCategories', count($categoryIds));
        }

        return $qb->orderBy('t.id', 'ASC')
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
