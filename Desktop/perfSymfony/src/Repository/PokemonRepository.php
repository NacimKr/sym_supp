<?php

namespace App\Repository;

use App\Entity\Pokemon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Pokemon>
 */
class PokemonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pokemon::class);
    }

    public function getNomPokemon():array
    {
        return $this->createQueryBuilder("p")
            ->select("p.name","p.content") //select pour selectionner un champs en particulier
            ->getQuery()
            ->getResult()// pour plusieurs resultat
            //->getSingleResult()// pour un seul resultat QUE SI ON A UNE SEUL LIGNE en ARRAY
            //->getSingleScalarResult()// pour un seul resultat QUE SI ON A UNE SEUL LIGNE en VALEUR directement
        ;
    }


    public function getAllPokemonWithType():array{
        return $this->createQueryBuilder("p")
            ->select("p","t","d")
            ->leftJoin("p.type","t") //Champs de laison, alias de la liaison
            ->leftJoin("p.dresseur","d")
            // ->andWhere("p.dresseur = 1")
            ->getQuery()
            ->getResult()
        ;
    }

    //    /**
    //     * @return Pokemon[] Returns an array of Pokemon objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Pokemon
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
