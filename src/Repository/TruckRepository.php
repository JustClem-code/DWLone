<?php

namespace App\Repository;

use App\Repository\Trait\RepositoryTrait;

use App\Entity\Truck;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use App\Repository\PalletRepository;

/**
 * @extends ServiceEntityRepository<Truck>
 */
class TruckRepository extends ServiceEntityRepository
{

  use RepositoryTrait;

  public function __construct(ManagerRegistry $registry, private PalletRepository $palletRepository)
  {
    parent::__construct($registry, Truck::class);
  }

  private function toArray(Truck $truck): array
  {
    return [
      'id' => $truck->getId(),
      'name' => $truck->getName(),
      'expectedDate' => $truck->getExpectedDate(),
      'deliveryDate' => $truck->getDeliveryDate(),
      'userDelDate' => $truck->getUserDelDate()?->getUsername(),
      'departureDate' => $truck->getDepartureDate(),
      'userDepDate' => $truck->getUserDepDate()?->getUsername(),
      'pallets' => $this->transFormEntities($truck->getPallets(), [$this->palletRepository, 'toArray']),
      'dock' => $truck->getDock()?->getName(),
    ];
  }

  public function transformAll(): array
  {
    return $this->transFormEntities($this->findAll(), [$this, 'toArray']);
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
