<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

  public function getSubscription($reportId, DateTime $reportDate)
  {
    $from = new DateTime($reportDate->format("Y-m-d")." 00:00:00");
    $to   = new DateTime($reportDate->format("Y-m-d")." 23:59:59");

    return $this->createQueryBuilder("e")
      ->where('e.reportId =:reportId')
      ->andWhere('e.startDate <= :from')
      ->andWhere('(e.endDate IS NULL OR e.endDate >= :to)')
      ->setParameter('reportId', $reportId)
      ->setParameter('from', $from)
      ->setParameter('to', $to)
      ->getQuery()
      ->getOneOrNullResult();
  }



    // /**
    //  * @return Order[] Returns an array of Order objects
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
    public function findOneBySomeField($value): ?Order
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
