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

    //returns number for daily total revenue
    public function sumOfDailyOrderRevenue($date) {
        return $this->createQueryBuilder('fo')
        ->select('SUM(fo.total) as totalRevenue')
        // found at https://stackoverflow.com/questions/11553183/select-entries-between-dates-in-doctrine-2
        ->andWhere('fo.dateCreated >= :date_start')
        ->andWhere('fo.dateCreated <= :date_end')
        ->setParameter('date_start', $date->format('Y-m-d 00:00:00'))
        ->setParameter('date_end',   $date->format('Y-m-d 23:59:59'))
        ->getQuery()
        ->getSingleScalarResult();
    }

    //returns number for daily total orders
    public function totalDailyOrders($date) {
        return $this->createQueryBuilder('fo')
        ->select('COUNT(fo.total) as totalOrders')
        // found at https://stackoverflow.com/questions/11553183/select-entries-between-dates-in-doctrine-2
        ->andWhere('fo.dateCreated >= :date_start')
        ->andWhere('fo.dateCreated <= :date_end')
        ->setParameter('date_start', $date->format('Y-m-d 00:00:00'))
        ->setParameter('date_end',   $date->format('Y-m-d 23:59:59'))
        ->getQuery()
        ->getSingleScalarResult();
    }
}
