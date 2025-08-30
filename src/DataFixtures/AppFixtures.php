<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Product;
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

  private function GenerateWrid()
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

  private  function GeneratePallets($manager, $truck): void
  {
    for ($i = 0; $i < 5; $i++) {
      $pallet = new Pallet();
      $pallet->setTruck($truck);
      $manager->persist($pallet);
    }
  }

  private function GenerateTrucks($manager): void
  {
    for ($i = 0; $i < 3; $i++) {
      $truck = new Truck();
      $truck->setWrid($this->GenerateWrid());
      $truck->setExpectedDate(new \DateTime());
      $manager->persist($truck);
      $this->GeneratePallets($manager, $truck);
    }
  }

  private function GenerateAddresses($manager, $customer): void
  {
    $this->faker = Factory::create();

    $address = new Address();
    $address->setStreetAddress($this->faker->streetAddress());
    $address->setCity($this->faker->city());
    $address->setPostcode($this->faker->postcode());
    $address->setCustomer($customer);
    $manager->persist($address);
  }

  private function GenerateCustomers($manager): void
  {
    $this->faker = Factory::create();

    for ($i = 0; $i < 1500; $i++) {
      $customer = new Customer();
      $customer->setfirstName($this->faker->firstName($gender = 'male' | 'female'));
      $customer->setLastname($this->faker->lastName());
      $manager->persist($customer);
      $this->GenerateAddresses($manager, $customer);
    }
  }

  private function GenerateRoles($manager): void
  {
    $OPS = new Role();
    $OPS->setName('OPS');
    $AS = new Role();
    $AS->setName('AS');
    $manager->persist($OPS);
    $manager->persist($AS);
    $manager->flush();
  }

  private function GenerateAssociates($manager): void
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

    $manager->flush();
  }
}
