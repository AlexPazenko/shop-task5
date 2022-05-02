<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Product::class);
        $this->paginator = $paginator;
    }

  public function sortByName(int $page, ?string $sort_method)
  {
    $sort_method = $sort_method ?? 'ASC';

    $dbquery = $this->createQueryBuilder('p')
      ->orderBy('p.name', $sort_method)
      ->getQuery();

    $pagination = $this->paginator->paginate($dbquery, $page, 2);
    return $pagination;
  }

  public function sortByPrice(int $page, ?string $sort_method)
  {
    $sort_method = $sort_method ?? 'ASC';

    $dbquery = $this->createQueryBuilder('p')
      ->orderBy('p.price', $sort_method)
      ->getQuery();

    $pagination = $this->paginator->paginate($dbquery, $page, 2);
    return $pagination;
  }

  public function sortByManufacturer(int $page, ?string $sort_method)
  {
    $sort_method = $sort_method ?? 'ASC';

    $dbquery = $this->createQueryBuilder('p')
      ->orderBy('p.manufacturer', $sort_method)
      ->getQuery();

    $pagination = $this->paginator->paginate($dbquery, $page, 2);
    return $pagination;
  }

  public function findByProductName(string $query, int $page, ?string $sort_method)
  {
    $sort_method = $sort_method ?? 'ASC';

    $querybuilder = $this->createQueryBuilder('p');
    $searchTerms = $this->prepareQuery($query);

    foreach ($searchTerms as $key => $term)
    {
      $querybuilder
        ->orWhere('p.name LIKE :t_'.$key)
        ->setParameter('t_'.$key, '%'.trim($term).'%');
    }
    $dbquery = $querybuilder
      ->orderBy('p.name', $sort_method)
      ->getQuery();
    return $this->paginator->paginate($dbquery, $page, 2);
  }


  private function prepareQuery(string $query): array
  {
    return explode(' ', $query);
  }


  /*  public function findAllProduct()
    {*/
        /*return $this->createQueryBuilder('p')
            ->innerJoin('a.files', 'f')
            ->andWhere('f INSTANCE OF App\Entity\Pdf')
            ->addSelect('f')
            ->getQuery()
            ->getOneOrNullResult()
            ;*/
        /*return $this->createQueryBuilder('p')
            ->select('p as products', 'u.first_name as user')
            ->leftJoin('p.user', 'u')
            ->getQuery();*/
           /* ->execute();*/

        /*return $this->createQueryBuilder('j')
            ->select('j as job', 'c.name as client')
            ->leftJoin('j.client', 'c')
            ->getQuery()
            ->execute();*/
/*    }*/

    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
