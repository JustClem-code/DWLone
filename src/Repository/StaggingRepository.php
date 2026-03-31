<?php

namespace App\Repository;

use App\Repository\Trait\RepositoryTrait;

use App\Entity\Stagging;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use App\Repository\CartRepository;

/**
 * @extends ServiceEntityRepository<Stagging>
 */
class StaggingRepository extends ServiceEntityRepository
{

  use RepositoryTrait;

  public function __construct(ManagerRegistry $registry, private CartRepository $cartRepository,)
  {
    parent::__construct($registry, Stagging::class);
  }

  private function toArray(Stagging $stagging): array
  {
    return [
      'id' => $stagging->getId(),
      'name' => $stagging->getName(),
      'road' => $stagging->getRoad() ? $stagging->getRoad()->getName() : '',
      'carts' => $this->transFormEntities($stagging->getCarts(), [$this->cartRepository, 'toArray']),
    ];
  }

  public function transformAll(): array
  {
    return $this->transFormEntities($this->findAll(), [$this, 'toArray']);
  }

  //    /**
  //     * @return Stagging[] Returns an array of Stagging objects
  //     */
  //    public function findByExampleField($value): array
  //    {
  //        return $this->createQueryBuilder('s')
  //            ->andWhere('s.exampleField = :val')
  //            ->setParameter('val', $value)
  //            ->orderBy('s.id', 'ASC')
  //            ->setMaxResults(10)
  //            ->getQuery()
  //            ->getResult()
  //        ;
  //    }

  //    public function findOneBySomeField($value): ?Stagging
  //    {
  //        return $this->createQueryBuilder('s')
  //            ->andWhere('s.exampleField = :val')
  //            ->setParameter('val', $value)
  //            ->getQuery()
  //            ->getOneOrNullResult()
  //        ;
  //    }
}
