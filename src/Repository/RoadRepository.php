<?php

namespace App\Repository;

use App\Repository\Trait\RepositoryTrait;

use App\Entity\Road;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use App\Repository\BagRepository;
use App\Repository\RoadPartRepository;

/**
 * @extends ServiceEntityRepository<Road>
 */
class RoadRepository extends ServiceEntityRepository
{
  use RepositoryTrait;

  public function __construct(
    ManagerRegistry $registry,
    private BagRepository $bagRepository,
    private RoadPartRepository $roadPartRepository
  ) {
    parent::__construct($registry, Road::class);
  }

  // TODO: Move in roadpart repository
  /* public function setBagsToNull(Road $road): void
  {
    foreach ($road->getBags() as $bag) {
      $bag->setRoadPartId(null);
    }
  } */

  public function toArray(Road $road): array
  {
    return [
      'id' => $road->getId(),
      'name' => $road->getName(),
      'stagging' => $road->getStagging(),
      'roadParts' => $this->transFormEntities($road->getRoadParts(), [$this->roadPartRepository, 'toArrayRoadOriented']),
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
