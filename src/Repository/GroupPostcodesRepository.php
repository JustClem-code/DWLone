<?php

namespace App\Repository;

use App\Repository\Trait\RepositoryTrait;

use App\Entity\GroupPostcodes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use App\Repository\PostcodesRepository;

/**
 * @extends ServiceEntityRepository<GroupPostcodes>
 */
class GroupPostcodesRepository extends ServiceEntityRepository
{

    use RepositoryTrait;

    public function __construct(ManagerRegistry $registry, private PostcodesRepository $postcodesRepository)
    {
        parent::__construct($registry, GroupPostcodes::class);
    }

    public function toArray(GroupPostcodes $groupPostcodes): array
    {
        return [
            'id' => $groupPostcodes->getId(),
            'name' => $groupPostcodes->getName(),
            'postcodes' => $this->transFormEntities($groupPostcodes->getPostcodes(), [$this->postcodesRepository, 'toArray']),
        ];
    }

    public function transformAll(): array
    {
        return $this->transFormEntities($this->findAll(), [$this, 'toArray']);
    }

    //    /**
    //     * @return GroupPostcodes[] Returns an array of GroupPostcodes objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('g')
    //            ->andWhere('g.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('g.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?GroupPostcodes
    //    {
    //        return $this->createQueryBuilder('g')
    //            ->andWhere('g.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
