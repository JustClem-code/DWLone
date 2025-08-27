<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Product;
use App\Entity\Truck;
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

  public function load(ObjectManager $manager): void
  {
    $this->faker = Factory::create();
    for ($i = 0; $i < 20; $i++) {
      $product = new Product();
      $product->setName($this->faker->words(4, true));
      $product->setPrice(mt_rand(10, 100));
      $manager->persist($product);
    }
    for ($i = 0; $i < 3; $i++) {
      $truck = new Truck();
      $truck->setWrid($this->GenerateWrid());
      $truck->setExpectedDate(new \DateTime());
      $manager->persist($truck);
    }

    $manager->flush();
  }
}
