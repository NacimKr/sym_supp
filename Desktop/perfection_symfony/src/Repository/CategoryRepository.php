<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Category>
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function categoryWithRecipe(string $value):array
    {
        return $this->createQueryBuilder('c')
            ->select('c', 'r') // je tout de category et recipe 
            ->leftJoin('c.recipes', 'r') // le c.recipes (table principal) à l'alias r
            // ->where('r.title = :val')
            // ->setParameter(':val', $value)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findAllWithCount()
    {
        return $this->createQueryBuilder("c")
            ->select('c', 'COUNT(c.id) as total')
            ->leftJoin('c.recipes', 'r')
            ->groupBy('c.id', 'c.libelle')
            ->getQuery()
            ->getResult()
        ;
    }

//    /**
//     * @return Category[] Returns an array of Category objects
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

//    public function findOneBySomeField($value): ?Category
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
