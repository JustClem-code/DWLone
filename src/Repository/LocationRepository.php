<?php

namespace App\Repository;

use App\Entity\Location;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Location>
 */
class LocationRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, Location::class);
  }

  /**
   * @return Location|null
   */
  public function findRandomWithoutPackage(): ?Location
  {
    $qb = $this->createQueryBuilder('l');

    $total = $qb->select('COUNT(l.id)')
      ->andWhere('l.packages IS EMPTY')
      ->getQuery()
      ->getSingleScalarResult();

    if ($total === 0) {
      return null;
    }

    $offset = random_int(0, $total - 1);

    return $qb->select('l')
      ->andWhere('l.packages IS EMPTY')
      ->setMaxResults(1)
      ->setFirstResult($offset)
      ->getQuery()
      ->getOneOrNullResult();
  }



  private function toArray(Location $location): array
  {
    return [
      'id' => $location->getId(),
      'name' => $location->getName(),
    ];
  }

  public function transformAll(): array
  {

    $entities = $this->findAll();

    $collection = [];

    foreach ($entities as $entity) {
      $collection[] = $this->toArray($entity);
    }

    return $collection;
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
