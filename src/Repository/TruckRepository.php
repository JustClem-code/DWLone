<?php

namespace App\Repository;

use App\Entity\Truck;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Truck>
 */
class TruckRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, Truck::class);
  }

  private function getPalletCollection($entities)
  {

    $collection = [];

    foreach ($entities as $entity) {
      $collection[] = [
        'id' => $entity->getId(),
        'associate' => $entity->getAssociate(),
      ];
    }

    return $collection;
  }

  private function toArray(Truck $truck): array
  {
    return [
      'id' => $truck->getId(),
      'wrid' => $truck->getWrid(),
      'expectedDate' => $truck->getExpectedDate(),
      'deliveryDate' => $truck->getDeliveryDate(),
      'pallets' => $this->getPalletCollection($truck->getPallets()),
    ];
  }

  public function transformAll()
  {

    $entities = $this->findAll();

    $collection = [];

    foreach ($entities as $entity) {
      $collection[] = $this->toArray($entity);
    }

    return $collection;
  }

  //    /**
  //     * @return Truck[] Returns an array of Truck objects
  //     */
  //    public function findByExampleField($value): array
  //    {
  //        return $this->createQueryBuilder('t')
  //            ->andWhere('t.exampleField = :val')
  //            ->setParameter('val', $value)
  //            ->orderBy('t.id', 'ASC')
  //            ->setMaxResults(10)
  //            ->getQuery()
  //            ->getResult()
  //        ;
  //    }

  //    public function findOneBySomeField($value): ?Truck
  //    {
  //        return $this->createQueryBuilder('t')
  //            ->andWhere('t.exampleField = :val')
  //            ->setParameter('val', $value)
  //            ->getQuery()
  //            ->getOneOrNullResult()
  //        ;
  //    }
}
