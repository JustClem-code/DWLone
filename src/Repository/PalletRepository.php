<?php

namespace App\Repository;

use App\Entity\Pallet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Pallet>
 */
class PalletRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, Pallet::class);
  }


  private function toArray(Pallet $pallet): array
  {
    return [
      'id' => $pallet->getId(),
      'userId' => $pallet->getUserId()?->getId(),
      'userName' => $pallet->getUserId()?->getUserName(),
    ];
  }

  public function findAllHasUser(): array
  {
    $entities = $this->createQueryBuilder('p')
      ->andWhere('p.UserId IS NOT NULL')
      ->orderBy('p.id', 'ASC')
      ->getQuery()
      ->getResult();

    $collection = [];

    foreach ($entities as $entity) {
      $collection[] = $this->toArray($entity);
    }

    return $collection;
  }

  //    /**
  //     * @return Pallet[] Returns an array of Pallet objects
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

  //    public function findOneBySomeField($value): ?Pallet
  //    {
  //        return $this->createQueryBuilder('p')
  //            ->andWhere('p.exampleField = :val')
  //            ->setParameter('val', $value)
  //            ->getQuery()
  //            ->getOneOrNullResult()
  //        ;
  //    }
}
