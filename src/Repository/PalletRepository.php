<?php

namespace App\Repository;

use App\Repository\Trait\RepositoryTrait;

use App\Entity\Pallet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use App\Repository\PackageRepository;

/**
 * @extends ServiceEntityRepository<Pallet>
 */
class PalletRepository extends ServiceEntityRepository
{

  use RepositoryTrait;

  public function __construct(ManagerRegistry $registry, private PackageRepository $packageRepository)
  {
    parent::__construct($registry, Pallet::class);
  }

  public function toArray(Pallet $pallet): array
  {
    return [
      'id' => $pallet->getId(),
      'userId' => $pallet->getUserId()?->getId(),
      'userName' => $pallet->getUserId()?->getUserName(),
      'truckName' => $pallet->getTruck()->getName(),
      'packages' => $this->transFormEntities($pallet->getPackages(), [$this->packageRepository, 'toArray']),
    ];
  }

  public function findAllHasUserAndPackageWithoutLocation(): array
  {
    $entities = $this->createQueryBuilder('p')
      ->leftJoin('p.packages', 'pa')
      ->andWhere('p.UserId IS NOT NULL')
      ->andWhere('pa.location IS NULL')
      ->orderBy('p.id', 'ASC')
      ->getQuery()
      ->getResult();

    return  $this->transFormEntities($entities, [$this, 'toArray']);
  }

  public function findAllHasUser(): array
  {
    $entities = $this->createQueryBuilder('p')
      ->andWhere('p.UserId IS NOT NULL')
      ->orderBy('p.id', 'ASC')
      ->getQuery()
      ->getResult();

    return $this->transFormEntities($entities, [$this, 'toArray']);
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
