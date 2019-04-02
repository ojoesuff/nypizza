<?php

namespace App\Repository;

use App\Entity\FinalOrder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method FinalOrder|null find($id, $lockMode = null, $lockVersion = null)
 * @method FinalOrder|null findOneBy(array $criteria, array $orderBy = null)
 * @method FinalOrder[]    findAll()
 * @method FinalOrder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FinalOrderRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FinalOrder::class);
    }

    // /**
    //  * @return FinalOrder[] Returns an array of FinalOrder objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FinalOrder
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    // public function sumOfTotalOrders()
    // {
    //     return $this->createQueryBuilder('fc')
    //         ->andWhere('fc.category = :category')
    //         ->setParameter('category', $category)
    //         ->select('SUM(fc.numberPrinted) as fortunesPrinted')
    //         ->getQuery()
    //         ->getSingleScalarResult();
    // }

    public function sumOfDailyOrderRevenue() {
        return $this->createQueryBuilder('fo')
        ->select('SUM(fo.total) as totalRevenue')
        ->getQuery()
        ->getSingleScalarResult();
    }
}
