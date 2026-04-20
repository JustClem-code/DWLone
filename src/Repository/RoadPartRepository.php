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


  public function setBagsToNull(RoadPart $roadPart): void
  {
    foreach ($roadPart->getBags() as $bag) {
      $bag->setRoadPart(null);
    }
  }

  public function toArray(RoadPart $roadPart): array
  {
    return [
      'id' => $roadPart->getId(),
      'number' => $roadPart->getNumber(),
      'road' => $roadPart->getRoad()->getName(),
      'bags' => $this->transFormEntities($roadPart->getBags(), [$this->bagRepository, 'toArrayRoadOriented']),
      'cart' => $roadPart->getCart() ? $roadPart->getCart() : '',
      'userName' => $roadPart->getUser() ? $roadPart->getUser()->getUsername() : '',
      'stagged' => $roadPart->isStagged()
    ];
  }

  public function toArrayRoadOriented(RoadPart $roadPart): array
  {
    return [
      'id' => $roadPart->getId(),
      'number' => $roadPart->getNumber(),
      'nbOfBags' => count($roadPart->getBags()),
      'bags' => $this->transFormEntities($roadPart->getBags(), [$this->bagRepository, 'toArrayRoadOriented']),
      'cart' => $roadPart->getCart() ? $roadPart->getCart() : '',
    ];
  }

  public function findFirstWithNoUser(): ?RoadPart
  {
    return $this->createQueryBuilder('r')
      ->innerJoin('r.road', 'ro')
      ->andWhere('r.user IS NULL')
      ->andWhere('r.stagged = FALSE')
      ->orderBy('ro.name', 'ASC')
      ->setMaxResults(1)
      ->getQuery()
      ->getOneOrNullResult();
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
