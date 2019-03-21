<?php

namespace App\Repository;

use App\Entity\CustomPizza;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CustomPizza|null find($id, $lockMode = null, $lockVersion = null)
 * @method CustomPizza|null findOneBy(array $criteria, array $orderBy = null)
 * @method CustomPizza[]    findAll()
 * @method CustomPizza[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomPizzaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CustomPizza::class);
    }

    // /**
    //  * @return CustomPizza[] Returns an array of CustomPizza objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CustomPizza
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
