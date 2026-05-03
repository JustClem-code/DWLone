<?php

namespace App\Repository;

use App\Repository\Trait\RepositoryTrait;

use App\Entity\Cart;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Cart>
 */
class CartRepository extends ServiceEntityRepository
{

  use RepositoryTrait;

  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, Cart::class);
  }

  public function toArray(Cart $cart): array
  {
    return [
      'id' => $cart->getId(),
      'roadPart' => $cart->getRoadPart() ? $cart->getRoadPart()->getRoad()->getName() . '#' . $cart->getRoadPart()->getNumber()  : '',
    ];
  }

  public function findOneWithoutRoadPart($value): ?Cart
  {
    return $this->createQueryBuilder('c')
      ->andWhere('c.stagging = :val')
      ->andWhere('c.roadPart IS NULL')
      ->setParameter('val', $value)
      ->setMaxResults(1)
      ->getQuery()
      ->getOneOrNullResult()
    ;
  }

  //    /**
  //     * @return Cart[] Returns an array of Cart objects
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

  //    public function findOneBySomeField($value): ?Cart
  //    {
  //        return $this->createQueryBuilder('c')
  //            ->andWhere('c.exampleField = :val')
  //            ->setParameter('val', $value)
  //            ->getQuery()
  //            ->getOneOrNullResult()
  //        ;
  //    }
}
