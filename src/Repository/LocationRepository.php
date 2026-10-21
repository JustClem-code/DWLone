<?php

namespace App\Repository;

use App\Repository\Trait\RepositoryTrait;

use App\Entity\Location;
use App\Entity\Aisle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use App\Repository\PackageRepository;

/**
 * @extends ServiceEntityRepository<Location>
 */
class LocationRepository extends ServiceEntityRepository
{

  use RepositoryTrait;

  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, Location::class);
  }

  public function findOneByAisle(Aisle $aisle): ?Location
  {
    return $this->createQueryBuilder('l')
      ->andWhere('l.aisle = :aisle')
      ->andWhere('l.packages IS EMPTY')
      ->orderBy('l.name', 'ASC')
      ->addOrderBy('l.name', 'ASC')
      ->setParameter('aisle', $aisle)
      ->setMaxResults(1)
      ->getQuery()
      ->getOneOrNullResult()
    ;
  }

  public function toArray(Location $location): array
  {
    // Need service to getPackages to avoid circular error
    return [
      'id' => $location->getId(),
      'name' => $location->getName(),
      'bag' => $location->getBag() ? $location->getBag()->getId() : null
    ];
  }

  public function transformAll(): array
  {
    return  $this->transFormEntities($this->findAll(), [$this, 'toArray']);
  }

  //    /**
  //     * @return Location[] Returns an array of Location objects
  //     */
  //    public function findByExampleField($value): array
  //    {
  //        return $this->createQueryBuilder('l')
  //            ->andWhere('l.exampleField = :val')
  //            ->setParameter('val', $value)
  //            ->orderBy('l.id', 'ASC')
  //            ->setMaxResults(10)
  //            ->getQuery()
  //            ->getResult()
  //        ;
  //    }

  //    public function findOneBySomeField($value): ?Location
  //    {
  //        return $this->createQueryBuilder('l')
  //            ->andWhere('l.exampleField = :val')
  //            ->setParameter('val', $value)
  //            ->getQuery()
  //            ->getOneOrNullResult()
  //        ;
  //    }
}
