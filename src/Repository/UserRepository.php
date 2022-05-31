<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, User::class);
      $this->paginator = $paginator;
    }

  public function filterByRole(int $page, ?string $roles, ?string $sort_method)
  {
    $sort_method = $sort_method ?? 'ASC';
    if($roles) {
    $dbquery = $this->createQueryBuilder('u')
      ->andWhere('u.roles = :roles')
      ->setParameter('roles', $roles)
      ->orderBy('u.roles', $sort_method)
      ->getQuery();
  } else {
      $dbquery = $this->createQueryBuilder('u')
        ->orderBy('u.roles', $sort_method)
        ->getQuery();
    }
    $pagination = $this->paginator->paginate($dbquery, $page, 2);
    return $pagination;
  }


  public function findByUsersEmail(string $query, int $page, ?string $role, ?string $sort_method)
  {
    $sort_method = $sort_method ?? 'ASC';

    $querybuilder = $this->createQueryBuilder('u');
    $searchTerms = $this->prepareQuery($query);

    foreach ($searchTerms as $key => $term)
    {
      $querybuilder
        ->orWhere('u.email LIKE :t_'.$key)
        ->setParameter('t_'.$key, '%'.trim($term).'%');

    }
    $dbquery = $querybuilder
      ->orderBy('u.roles', $sort_method)
      ->getQuery();
    return $this->paginator->paginate($dbquery, $page, 2);
  }


  private function prepareQuery(string $query): array
  {
    return explode(' ', $query);
  }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
