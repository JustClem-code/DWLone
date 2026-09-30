<?php

namespace App\Repository;

use App\Repository\Trait\RepositoryTrait;

use App\Entity\Postcodes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Postcodes>
 */
class PostcodesRepository extends ServiceEntityRepository
{

  use RepositoryTrait;

  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, Postcodes::class);
  }

   public function toArray(Postcodes $postcodes): array
  {
    return [
      'id' => $postcodes->getId(),
      'name' => $postcodes->getName(),
    ];
  }

  public function transformAll(): array
  {
    return $this->transFormEntities($this->findAll(), [$this, 'toArray']);
  }

  //    /**
  //     * @return Postcodes[] Returns an array of Postcodes objects
  //     */
  //    public function findByExampleField($value): array
  //    {
  //        return $this->createQueryBuilder('p')
  //            ->andWhere('p.exampleField = :val')
  //            ->setParameter('val', $value)
  //            ->orderBy('p.id', 'ASC')
  //            ->setMaxResults(10)
  //            ->getQuery()
  //            ->getResult()
  //        ;
  //    }

  //    public function findOneBySomeField($value): ?Postcodes
  //    {
  //        return $this->createQueryBuilder('p')
  //            ->andWhere('p.exampleField = :val')
  //            ->setParameter('val', $value)
  //            ->getQuery()
  //            ->getOneOrNullResult()
  //        ;
  //    }
}
