<?php

namespace App\Repository;

use App\Repository\Trait\RepositoryTrait;

use App\Entity\Dock;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use App\Repository\PalletRepository;

/**
 * @extends ServiceEntityRepository<Dock>
 */
class DockRepository extends ServiceEntityRepository
{

  use RepositoryTrait;

  public function __construct(ManagerRegistry $registry, private PalletRepository $palletRepository)
  {
    parent::__construct($registry, Dock::class);
  }

  private function toArray(Dock $dock): array
  {
    return [
      'id' => $dock->getId(),
      'name' => $dock->getName(),
      'truckId' => $dock->getTruck()?->getId(),
      'truckName' => $dock->getTruck()?->getName(),
      'pallets' => $dock->getTruck() ? $this->transFormEntities($dock->getTruck()?->getPallets(), [$this->palletRepository, 'toArray']) : null,
    ];
  }

  public function transformAll(): array
  {
    return  $this->transFormEntities($this->findAll(), [$this, 'toArray']);
  }

  public function transformOccupiedDocks(): array
  {
    $entities = $this->createQueryBuilder('d')
      ->innerJoin('d.truck', 't')
      ->addSelect('t')
      ->orderBy('d.id', 'ASC')
      ->getQuery()
      ->getResult();

    return  $this->transFormEntities($entities, [$this, 'toArray']);
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
