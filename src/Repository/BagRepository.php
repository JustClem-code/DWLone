<?php

namespace App\Repository;

use App\Entity\Bag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Bag>
 */
class BagRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, Bag::class);
  }

  public function findRandomWithoutLocation(): ?Bag
  {
    $qbCount = $this->createQueryBuilder('b')
      ->select('COUNT(b.id)')
      ->where('b.location IS NULL');

    $total = $qbCount->getQuery()->getSingleScalarResult();

    if ($total === 0) {
      return null;
    }

    $offset = random_int(0, $total - 1);

    return $this->createQueryBuilder('b')
      ->where('b.location IS NULL')
      ->setFirstResult($offset)
      ->setMaxResults(1)
      ->getQuery()
      ->getOneOrNullResult();
  }

  public function findAllHasLocation(): array
  {
    return $this->createQueryBuilder('b')
      ->andWhere('b.location IS NOT NULL')
      ->orderBy('b.id', 'ASC')
      ->getQuery()
      ->getResult();
  }

  public function toArray(Bag $bag): array
  {
    return [
      'id' => $bag->getId(),
      'name' => $bag->getName(),
      'location' => $bag->getLocation(),
    ];
  }

  //    /**
  //     * @return Bag[] Returns an array of Bag objects
  //     */
  //    public function findByExampleField($value): array
  //    {
  //        return $this->createQueryBuilder('b')
  //            ->andWhere('b.exampleField = :val')
  //            ->setParameter('val', $value)
  //            ->orderBy('b.id', 'ASC')
  //            ->setMaxResults(10)
  //            ->getQuery()
  //            ->getResult()
  //        ;
  //    }

  //    public function findOneBySomeField($value): ?Bag
  //    {
  //        return $this->createQueryBuilder('b')
  //            ->andWhere('b.exampleField = :val')
  //            ->setParameter('val', $value)
  //            ->getQuery()
  //            ->getOneOrNullResult()
  //        ;
  //    }
}
