<?php

namespace App\Repository;

use App\Repository\Trait\RepositoryTrait;

use App\Entity\RoadPart;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use App\Repository\BagRepository;

/**
 * @extends ServiceEntityRepository<RoadPart>
 */
class RoadPartRepository extends ServiceEntityRepository
{

  use RepositoryTrait;

  public function __construct(ManagerRegistry $registry, private BagRepository $bagRepository)
  {
    parent::__construct($registry, RoadPart::class);
  }

  public function toArray(RoadPart $roadPart): array
  {
    return [
      'id' => $roadPart->getId(),
      'number' => $roadPart->getNumber(),
      'road' => $roadPart->getRoad(),
      'bags' => $this->transFormEntities($roadPart->getBags(), [$this->bagRepository, 'toArrayRoadOriented']),
      'cart' => $roadPart->getCart() ? $roadPart->getCart() : '',
    ];
  }


  //    /**
  //     * @return RoadPart[] Returns an array of RoadPart objects
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

  //    public function findOneBySomeField($value): ?RoadPart
  //    {
  //        return $this->createQueryBuilder('r')
  //            ->andWhere('r.exampleField = :val')
  //            ->setParameter('val', $value)
  //            ->getQuery()
  //            ->getOneOrNullResult()
  //        ;
  //    }
}
