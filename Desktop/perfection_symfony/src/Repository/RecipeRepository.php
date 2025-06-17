<?php

namespace App\Repository;

use App\Entity\Recipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recipe>
 */
class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

    public function getSumDurationRecipe():mixed
    {
        return $this->createQueryBuilder("r")
            ->select("SUM(r.duration) as total")
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    public function recipeLessThan10Minutes(int $duration) : array
    {
        return $this->createQueryBuilder("r")
            ->andWhere('r.duration < :duration')
            ->setParameter('duration', $duration)
            ->getQuery()
            ->getResult()
            ;
    }

    public function recipeWithCategory(): array
    {
        return $this->createQueryBuilder('r')
            ->select('r', 'c')
            ->leftJoin('r.category', 'c')
            ->getQuery()
            ->getResult()
            ;
    }

    //    /**
    //     * @return Recipe[] Returns an array of Recipe objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Recipe
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
