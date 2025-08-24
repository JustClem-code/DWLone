<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
  protected $faker;
  public function load(ObjectManager $manager): void
  {
    $this->faker = Factory::create();
    for ($i = 0; $i < 20; $i++) {
      $product = new Product();
      $product->setName($this->faker->words(4, true));
      $product->setPrice(mt_rand(10, 100));
      $manager->persist($product);
    }

    $manager->flush();
  }
}
