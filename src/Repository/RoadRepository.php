<?php

namespace App\Repository;

use App\Repository\Trait\RepositoryTrait;

use App\Entity\Road;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use App\Repository\BagRepository;

/**
 * @extends ServiceEntityRepository<Road>
 */
class RoadRepository extends ServiceEntityRepository
{
  use RepositoryTrait;

  public function __construct(ManagerRegistry $registry, private BagRepository $bagRepository)
  {
    parent::__construct($registry, Road::class);
  }

  public function setBagsToNull(Road $road): void
  {
    foreach ($road->getBags() as $bag) {
      $bag->setRoad(null);
    }
  }

  public function toArray(Road $road): array
  {
    return [
      'id' => $road->getId(),
      'name' => $road->getName(),
      'stagging' => $road->getStagging(),
      'carts' => $road->getCarts(),
      'bags' => $this->transFormEntities($road->getBags(), [$this->bagRepository, 'toArrayRoadOriented']),
    ];
  }

  public function transformAll(iterable $entities): array
  {
    return  $this->transFormEntities($entities, [$this, 'toArray']);
  }

  //    /**
  //     * @return Road[] Returns an array of Road objects
  //     */
  //    public function findByExampleField($value): array
  //    {
  //        return $this->createQueryBuilder('r')
  //            ->andWhere('r.exampleField = :val')
  //            ->setParameter('val', $value)
  //            ->orderBy('r.id', 'ASC')
  //            ->setMaxResults(10)
  //            ->getQuery()
  //            ->getResult()
  //        ;
  //    }

  //    public function findOneBySomeField($value): ?Road
  //    {
  //        return $this->createQueryBuilder('r')
  //            ->andWhere('r.exampleField = :val')
  //            ->setParameter('val', $value)
  //            ->getQuery()
  //            ->getOneOrNullResult()
  //        ;
  //    }
}
