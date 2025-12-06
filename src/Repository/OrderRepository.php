<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use App\Repository\AddressRepository;
use App\Repository\CustomerRepository;

/**
 * @extends ServiceEntityRepository<Order>
 */
class OrderRepository extends ServiceEntityRepository
{
  public function __construct(
    ManagerRegistry $registry,
    private AddressRepository $addressRepository,
    private CustomerRepository $customerRepository
  ) {
    parent::__construct($registry, Order::class);
  }

  public function toArray(Order $order): array
  {
    return [
      'id' => $order->getId(),
      'address' => $this->addressRepository->toArray($order->getAddress()),
      'customer' => $this->customerRepository->toArray($order->getCustomer()),
    ];
  }

  //    /**
  //     * @return Order[] Returns an array of Order objects
  //     */
  //    public function findByExampleField($value): array
  //    {
  //        return $this->createQueryBuilder('o')
  //            ->andWhere('o.exampleField = :val')
  //            ->setParameter('val', $value)
  //            ->orderBy('o.id', 'ASC')
  //            ->setMaxResults(10)
  //            ->getQuery()
  //            ->getResult()
  //        ;
  //    }

  //    public function findOneBySomeField($value): ?Order
  //    {
  //        return $this->createQueryBuilder('o')
  //            ->andWhere('o.exampleField = :val')
  //            ->setParameter('val', $value)
  //            ->getQuery()
  //            ->getOneOrNullResult()
  //        ;
  //    }
}
