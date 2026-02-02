<?php

namespace App\Repository;

use App\Repository\Trait\RepositoryTrait;

use App\Entity\Package;
use App\Entity\Location;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use App\Repository\OrderRepository;
use App\Repository\BagRepository;
use App\Repository\LocationRepository;

/**
 * @extends ServiceEntityRepository<Package>
 */
class PackageRepository extends ServiceEntityRepository
{

  use RepositoryTrait;

  public function __construct(
    ManagerRegistry $registry,
    private OrderRepository $orderRepository,
    private BagRepository $bagRepository,
    private LocationRepository $locationRepository,
  ) {
    parent::__construct($registry, Package::class);
  }

  public function findAllFromPalletsWithUser(): array
  {
    return $this->createQueryBuilder('pa')
      ->innerJoin('pa.Pallet', 'p')
      ->andWhere('p.UserId IS NOT NULL')
      ->orderBy('pa.id', 'ASC')
      ->getQuery()
      ->getResult();
  }

  public function findAllWithoutLocationFromPalletsWithUser(): array
  {
    return $this->createQueryBuilder('pa')
      ->innerJoin('pa.Pallet', 'p')
      ->andWhere('p.UserId IS NOT NULL')
      ->andWhere('pa.location IS NULL')
      ->orderBy('pa.id', 'ASC')
      ->getQuery()
      ->getResult();
  }

  public function findAllHasLocation(): array
  {
    return $this->createQueryBuilder('p')
      ->andWhere('p.location IS NOT NULL')
      ->orderBy('p.id', 'ASC')
      ->getQuery()
      ->getResult();
  }
  
  public function findAllWithLocationAndNotStowed(): array
  {
    return $this->createQueryBuilder('pa')
      ->andWhere('pa.location IS NOT NULL')
      ->andWhere('pa.UserStow IS NULL')
      ->orderBy('pa.id', 'ASC')
      ->getQuery()
      ->getResult();
  }


  private function findPostcode(Package $package): int
  {
    return $package->getOrderId()->getAddress()->getPostcode();
  }

  public function findLocationBySamePostcode(Package $package): ?Location
  {
    $targetPostcode = $this->findPostcode($package);
    $packagesWithLocation = $this->findAllHasLocation();

    foreach ($packagesWithLocation as $pkg) {
      if ($this->findPostcode($pkg) === $targetPostcode && $pkg->getLocation() !== null) {
        return $pkg->getLocation();
      }
    }

    return null;
  }

  public function toArray(Package $package): array
  {
    return [
      'id' => $package->getId(),
      'weight' => $package->getWeight(),
      'location' => $package->getLocation() ? $this->locationRepository->toArray($package->getLocation()) : null,
      'bag' => $package->getBag() ? $this->bagRepository->toArray($package->getBag()) : null,
      'order' => $this->orderRepository->toArray($package->getOrderId()),
      'userStow' => $package->getUserStow()?->getUsername(),
    ];
  }

  public function toArrayBagOriented(Package $package): array
  {
    return [
      'id' => $package->getId(),
      'weight' => $package->getWeight(),
      'totalWeight' => $package->getWeight() + $package->getPackaging()->getWeight(),
      'postcode' => $package->getOrderId()->getAddress()->getPostcode(),
      'userStow' => $package->getUserStow()?->getUsername(),
    ];
  }

  // Not used
  public function transformCollection($entities): array
  {
    return $this->transFormEntities($entities, [$this, 'toArray']);
  }

  //    /**
  //     * @return Package[] Returns an array of Package objects
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

  //    public function findOneBySomeField($value): ?Package
  //    {
  //        return $this->createQueryBuilder('p')
  //            ->andWhere('p.exampleField = :val')
  //            ->setParameter('val', $value)
  //            ->getQuery()
  //            ->getOneOrNullResult()
  //        ;
  //    }
}
