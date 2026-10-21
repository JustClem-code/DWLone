<?php

namespace App\Repository;

use App\Entity\Aisle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Aisle>
 */
class AisleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Aisle::class);
    }

    /**
     * @return Aisle|null
     */
    public function findByRandomAisleWithoutGroupPostcode(): ?Aisle
    {
        $qb = $this->createQueryBuilder('a')
            ->andWhere('a.groupPostcodes IS EMPTY');

        $total = (int) (clone $qb)
            ->select('COUNT(a.id)')
            ->getQuery()
            ->getSingleScalarResult();

        if ($total > 0) {
            $offset = random_int(0, $total - 1);

            return $qb
                ->select('a')
                ->orderBy('a.id', 'ASC')
                ->setFirstResult($offset)
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult();
        }

        return $this->createQueryBuilder('a')
            ->select('a', 'COUNT(g.id) AS HIDDEN groupCount')
            ->leftJoin('a.groupPostcodes', 'g')
            ->groupBy('a.id')
            ->orderBy('groupCount', 'ASC')
            ->addOrderBy('a.id', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    //    /**
    //     * @return Aisle[] Returns an array of Aisle objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Aisle
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
