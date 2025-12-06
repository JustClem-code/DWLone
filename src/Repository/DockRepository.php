<?php

namespace App\Repository;

use App\Entity\Dock;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use App\Repository\PalletRepository;

/**
 * @extends ServiceEntityRepository<Dock>
 */
class DockRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry, private PalletRepository $palletRepository)
  {
    parent::__construct($registry, Dock::class);
  }

  private function getPalletCollection($entities)
  {

    $collection = [];

    foreach ($entities as $entity) {
      $collection[] = $this->palletRepository->toArray($entity);
    }

    return $collection;
  }

  private function toArray(Dock $dock): array
  {
    return [
      'id' => $dock->getId(),
      'name' => $dock->getName(),
      'truckId' => $dock->getTruck()?->getId(),
      'truckName' => $dock->getTruck()?->getName(),
      'pallets' => $dock->getTruck() ? $this->getPalletCollection($dock->getTruck()?->getPallets()) : null,
    ];
  }

  public function transformAll(): array
  {

    $entities = $this->findAll();

    $collection = [];

    foreach ($entities as $entity) {
      $collection[] = $this->toArray($entity);
    }

    usort($collection, function ($a, $b) {
      return $a['id'] <=> $b['id'];
    });

    return $collection;
  }

  public function transformOccupiedDocks(): array
{
    $entities = $this->createQueryBuilder('d')
        ->innerJoin('d.truck', 't')
        ->addSelect('t')
        ->orderBy('d.id', 'ASC')
        ->getQuery()
        ->getResult();

    $collection = [];

    foreach ($entities as $entity) {
        $collection[] = $this->toArray($entity);
    }

    return $collection;
}


  //    /**
  //     * @return Dock[] Returns an array of Dock objects
  //     */
  //    public function findByExampleField($value): array
  //    {
  //        return $this->createQueryBuilder('d')
  //            ->andWhere('d.exampleField = :val')
  //            ->setParameter('val', $value)
  //            ->orderBy('d.id', 'ASC')
  //            ->setMaxResults(10)
  //            ->getQuery()
  //            ->getResult()
  //        ;
  //    }

  //    public function findOneBySomeField($value): ?Dock
  //    {
  //        return $this->createQueryBuilder('d')
  //            ->andWhere('d.exampleField = :val')
  //            ->setParameter('val', $value)
  //            ->getQuery()
  //            ->getOneOrNullResult()
  //        ;
  //    }
}
