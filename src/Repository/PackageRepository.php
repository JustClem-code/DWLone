<?php

namespace App\Repository;

use App\Entity\Package;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use App\Repository\OrderRepository;

/**
 * @extends ServiceEntityRepository<Package>
 */
class PackageRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry, private OrderRepository $orderRepository)
  {
    parent::__construct($registry, Package::class);
  }

  public function toArray(Package $package): array
  {
    return [
      'id' => $package->getId(),
      'weight' => $package->getWeight(),
      'location' => $package->getLocation(),
      'order' => $this->orderRepository->toArray($package->getOrderId()),
    ];
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
