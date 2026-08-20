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

  public function hasInductedPackages(Pallet $pallet): bool
  {
    return null !== $this->createQueryBuilder('p')
      ->select('p.id')
      ->andWhere('p = :pallet')
      ->leftJoin('p.packages', 'pa')
      ->andWhere('pa.location IS NOT NULL')
      ->setParameter('pallet', $pallet)
      ->getQuery()
      ->getOneOrNullResult();
  }

  private function findAllHasUserAndPackageWithoutLocation(): array
  {
    return $this->createQueryBuilder('p')
      ->leftJoin('p.packages', 'pa')
      ->andWhere('p.UserId IS NOT NULL')
      ->andWhere('pa.location IS NULL')
      ->orderBy('p.id', 'ASC')
      ->getQuery()
      ->getResult();
  }

  public function findAllWithUserAndWithoutPackageInducted(): array
  {
    return $this->createQueryBuilder('p')
      ->andWhere('p.UserId IS NOT NULL')
      ->leftJoin(
        'p.packages',
        'pa',
        'WITH',
        'pa.location IS NOT NULL'
      )
      ->andWhere('pa.id IS NULL')
      ->orderBy('p.id', 'ASC')
      ->getQuery()
      ->getResult();
  }

  public function findAllWithUser(): array
  {
    return $this->createQueryBuilder('p')
      ->andWhere('p.UserId IS NOT NULL')
      ->orderBy('p.id', 'ASC')
      ->getQuery()
      ->getResult();
  }

  public function findAllWithoutUser(): array
  {
    return $this->createQueryBuilder('p')
      ->andWhere('p.UserId IS NULL')
      ->orderBy('p.id', 'ASC')
      ->getQuery()
      ->getResult();
  }

  public function transformAllWithUser(): array
  {
    return $this->transFormEntities($this->findAllWithUser(), [$this, 'toArray']);
  }

  public function transformAllHasUserAndPackageWithoutLocation(): array
  {
    return $this->transFormEntities($this->findAllHasUserAndPackageWithoutLocation(), [$this, 'toArray']);
  }

  public function transformAll(): array
  {
    return $this->transFormEntities($this->findAll(), [$this, 'toArray']);
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
