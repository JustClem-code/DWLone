<?php

namespace App\Repository;

use App\Entity\Pallet;
use App\Entity\Order;
use App\Entity\Address;
use App\Entity\Customer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Pallet>
 */
class PalletRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, Pallet::class);
  }

  private function getAddressDetails(Address $address): array
  {
     return [
      'id' => $address->getId(),
      'streetAddress' => $address->getStreetAddress(),
      'postcode' => $address->getPostcode(),
      'city' => $address->getCity(),
    ];
  }

  private function getCustomerDetails(Customer $customer) : array
  {
    return [
      'id' => $customer->getId(),
      'firstname' => $customer->getFirstname(),
      'lastname' => $customer->getLastname(),
    ];
  }

  private function getOrderDetails(Order $order): array
  {
    return [
      'id' => $order->getId(),
      'address' => $this->getAddressDetails($order->getAddress()),
      'customer' => $this->getCustomerDetails($order->getCustomer()),
    ];
  }

  private function getPackagesCollection($entities)
  {

    $collection = [];

    foreach ($entities as $entity) {
      $collection[] = [
        'id' => $entity->getId(),
        'weight' => $entity->getWeight(),
        'location' => $entity->getLocation(),
        'order' => $this->getOrderDetails($entity->getOrderId()),
      ];
    }

    return $collection;
  }

  public function toArray(Pallet $pallet): array
  {
    return [
      'id' => $pallet->getId(),
      'userId' => $pallet->getUserId()?->getId(),
      'userName' => $pallet->getUserId()?->getUserName(),
      'truckName' => $pallet->getTruck()->getName(),
      'packages' => $this->getPackagesCollection($pallet->getPackages()),
    ];
  }

  public function findAllHasUser(): array
  {
    $entities = $this->createQueryBuilder('p')
      ->andWhere('p.UserId IS NOT NULL')
      ->orderBy('p.id', 'ASC')
      ->getQuery()
      ->getResult();

    $collection = [];

    foreach ($entities as $entity) {
      $collection[] = $this->toArray($entity);
    }

    return $collection;
  }

  //    /**
  //     * @return Pallet[] Returns an array of Pallet objects
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

  //    public function findOneBySomeField($value): ?Pallet
  //    {
  //        return $this->createQueryBuilder('p')
  //            ->andWhere('p.exampleField = :val')
  //            ->setParameter('val', $value)
  //            ->getQuery()
  //            ->getOneOrNullResult()
  //        ;
  //    }
}
