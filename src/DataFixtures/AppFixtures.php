<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Product;
use App\Entity\Stagging;
use App\Entity\DeliveryPerson;
use App\Entity\DeliveryCompany;
use App\Entity\Package;
use App\Entity\Order;
use App\Entity\Packaging;
use App\Entity\Customer;
use App\Entity\Role;
use App\Entity\Associate;
use App\Entity\Address;
use App\Entity\Truck;
use App\Entity\Pallet;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\DBAL\Types\StringType;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
  protected $faker;

  private function GenerateWrid(): string
  {

    $number = '0123456789';
    $letter = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    $part1 = '';
    for ($i = 0; $i < 3; $i++) {
      $part1 .= $number[random_int(0, strlen($number) - 1)];
    }

    $part2 = '';
    for ($i = 0; $i < 2; $i++) {
      $part2 .= $letter[random_int(0, strlen($letter) - 1)];
    }

    $part3 = '';
    for ($i = 0; $i < 2; $i++) {
      $part3 .= $number[random_int(0, strlen($number) - 1)];
    }

    return $part1 . $part2 . $part3;
  }

  private  function GeneratePallets(mixed $manager, mixed $truck): void
  {
    for ($i = 0; $i < 5; $i++) {
      $pallet = new Pallet();
      $pallet->setTruck($truck);
      $manager->persist($pallet);
    }
  }

  private function GenerateTrucks(mixed $manager): void
  {
    for ($i = 0; $i < 3; $i++) {
      $truck = new Truck();
      $truck->setWrid($this->GenerateWrid());
      $truck->setExpectedDate(new \DateTime());
      $manager->persist($truck);
      $this->GeneratePallets($manager, $truck);
    }
  }

  private function GenerateAddresses(mixed $manager, mixed $customer): void
  {
    $this->faker = Factory::create();

    $address = new Address();
    $address->setStreetAddress($this->faker->streetAddress());
    $address->setCity($this->faker->city());
    $address->setPostcode($this->faker->postcode());
    $address->setCustomer($customer);
    $manager->persist($address);
  }

  private function GenerateCustomers(mixed $manager): void
  {
    $this->faker = Factory::create();

    for ($i = 0; $i < 500; $i++) {
      $customer = new Customer();
      $customer->setfirstName($this->faker->firstName($gender = 'male' | 'female'));
      $customer->setLastname($this->faker->lastName());
      $manager->persist($customer);
      $this->GenerateAddresses($manager, $customer);
    }
  }

  private function GenerateRoles(mixed $manager): void
  {
    $OPS = new Role();
    $OPS->setName('OPS');
    $AS = new Role();
    $AS->setName('AS');
    $manager->persist($OPS);
    $manager->persist($AS);

    $manager->flush();
  }

  private function GenerateAssociates(mixed $manager): void
  {
    $this->faker = Factory::create();

    for ($i = 0; $i < 10; $i++) {
      $associate = new Associate();
      $firstname = $this->faker->firstName($gender = 'male' | 'female');
      $lastname = $this->faker->lastName();
      $username = substr($firstname, 0, 3) . substr($lastname, 0, 3);

      $RoleRepository = $manager->getRepository(Role::class);
      $associateRole = $RoleRepository->findOneBy(['name' => 'AS']);

      $associate->setUsername($username);
      $associate->setfirstName($firstname);
      $associate->setLastname($lastname);
      $associate->setRole($associateRole);
      $manager->persist($associate);
    }
  }

  private function GeneratePackagings(mixed $manager): void
  {

    $packs = [
      [
        'name' => 'A2',
        'weight' => 500,
        'lenght' => 30,
        'width' => 40,
        'height' => 20,
        'oversize' => false
      ],
      [
        'name' => 'B5',
        'weight' => 200,
        'lenght' => 20,
        'width' => 30,
        'height' => 15,
        'oversize' => false
      ],
      [
        'name' => 'C6',
        'weight' => 50,
        'lenght' => 50,
        'width' => 45,
        'height' => 5,
        'oversize' => false
      ]
    ];

    foreach ($packs as $pack) {
      $packaging = new Packaging();
      $packaging->setName($pack['name']);
      $packaging->setWeight($pack['weight']);
      $packaging->setLenght($pack['lenght']);
      $packaging->setWidth($pack['width']);
      $packaging->setHeight($pack['height']);
      $packaging->setOversize($pack['oversize']);
      $manager->persist($packaging);
    }

    $manager->flush();
  }

  private function GeneratePackages(mixed $manager, mixed $order)
  {

    $PackagingRepository = $manager->getRepository(Packaging::class);
    $packagings = $PackagingRepository->findAll();
    shuffle($packagings);

    $package = new Package();
    $package->setWeight(mt_rand(50, 2000));
    $package->setPackaging($packagings[0]);
    $package->setOrderId($order);
    $manager->persist($package);
  }

  private function GenerateOrders(mixed $manager): void
  {
    $CustomerRepository = $manager->getRepository(Customer::class);
    $AddressRepository = $manager->getRepository(Address::class);

    $customers = $CustomerRepository->findAll();

    foreach ($customers as $customer) {
      $order = new Order();
      $order->setCustomer($customer);

      $customerId = $customer->getId();

      $address = $AddressRepository->findOneBy(['customer' => $customerId]);

      /* $customerAddresses = $customer->getAddresses(); */

      $order->setAddress($address);
      $manager->persist($order);

      $this->GeneratePackages($manager, $order);
    }
  }

  private function GenerateDeliveryPeople(mixed $manager, $DeliveryCompany): void
  {
    $this->faker = Factory::create();

    for ($i = 0; $i < 5; $i++) {
      $deliveryPerson = new DeliveryPerson();
      $firstname = $this->faker->firstName($gender = 'male' | 'female');
      $lastname = $this->faker->lastName();
      $username = substr($firstname, 0, 3) . substr($lastname, 0, 3);

      $deliveryPerson->setUsername($username);
      $deliveryPerson->setfirstName($firstname);
      $deliveryPerson->setLastname($lastname);
      $deliveryPerson->setCompany($DeliveryCompany);

      $manager->persist($deliveryPerson);
    }
  }

  private function GenerateDeliveryCompany(mixed $manager): void
  {
    $companiyNames = ['ATA', 'CNPS', 'Coliwest', 'HTDS', 'RAIZ'];

    foreach ($companiyNames as $companiyName) {
      $DeliveryCompany = new DeliveryCompany();
      $DeliveryCompany->setName($companiyName);

      $manager->persist($DeliveryCompany);

      $this->GenerateDeliveryPeople($manager, $DeliveryCompany);
    }
  }

  private function GenerateStaggings(mixed $manager): void
  {
    $letters = ['A', 'B','C', 'D', 'E', 'G'];

    foreach ($letters as $letter) {
      $staggingNumber = 1;
      for ($i=0; $i < 6; $i++) {
        $stagging = New Stagging();
        $stagging->setName($letter . '-' . $staggingNumber);
        $staggingNumber++;
        $manager->persist($stagging);
      }
    }
  }

  public function load(ObjectManager $manager): void
  {
    /* TEST PRODUCT */
    /* $this->faker = Factory::create();
    for ($i = 0; $i < 20; $i++) {
      $product = new Product();
      $product->setName($this->faker->words(4, true));
      $product->setPrice(mt_rand(10, 100));
      $manager->persist($product);
    } */

    $this->GenerateTrucks($manager);
    $this->GenerateCustomers($manager);
    $this->GenerateRoles($manager);
    $this->GenerateAssociates($manager);
    $this->GeneratePackagings($manager);
    $this->GenerateOrders($manager);
    $this->GenerateDeliveryCompany($manager);
    $this->GenerateStaggings($manager);

    $manager->flush();
  }
}
