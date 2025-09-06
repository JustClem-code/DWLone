<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Bag;
use App\Entity\Location;
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
use App\Entity\Pallet;
use App\Entity\Truck;
use App\Entity\Dock;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\DBAL\Types\StringType;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
  protected $faker;

  private function generateWrid(): string
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

  private function GenerateDocks(mixed $manager): void
  {
    $dockNumber = 1;
    for ($i = 0; $i < 11; $i++) {
      $dock = New Dock();
      $dock->setName('OB_' . $dockNumber);
      $manager->persist($dock);
      $dockNumber++;
    }
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
      $truck->setWrid($this->generateWrid());
      $truck->setExpectedDate(new \DateTime());
      $manager->persist($truck);
      $this->generatePallets($manager, $truck);
    }
  }

  private function generateAddress(mixed $manager, mixed $customer): void
  {
    $this->faker = Factory::create();

    $postCodes = [
      "Abbaretz" => "44170",
      "Aigrefeuille-sur-Maine" => "44140",
      "Ancenis" => "44150",
      "Anetz" => "44150",
      "Arthon-en-Retz" => "44320",
      "Assérac" => "44410",
      "Avessac" => "44460",
      "Barbechat" => "44450",
      "Basse-Goulaine" => "44115",
      "Batz-sur-Mer" => "44740",
      "Besné" => "44160",
      "Blain" => "44130",
      "Bonnoeuvre" => "44540",
      "Bouaye" => "44830",
      "Bouguenais" => "44340",
      "Bourgneuf-en-Retz" => "44580",
      "Boussay" => "44190",
      "Bouvron" => "44130",
      "Brains" => "44830",
      "Campbon" => "44750",
      "Carquefou" => "44470",
      "Casson" => "44390",
      "Château-Thébaud" => "44690",
      "Châteaubriant" => "44110",
      "Cheix-en-Retz" => "44640",
      "Clisson" => "44190",
      "Cordemais" => "44360",
      "Couëron" => "44220",
      "Donges" => "44480",
      "Frossay" => "44320",
      "Guenrouet" => "44530",
      "Guérande" => "44350",
      "Haute-Goulaine" => "44115",
      "Indre" => "44610",
      "La Baule-Escoublac" => "44500",
      "La Chapelle-sur-Erdre" => "44240",
      "La Chevrolière" => "44118",
      "La Montagne" => "44620",
      "La Planche" => "44140",
      "La Turballe" => "44420",
      "Le Croisic" => "44490",
      "Le Loroux-Bottereau" => "44430",
      "Le Pallet" => "44330",
      "Le Pellerin" => "44640",
      "Le Pin" => "44540",
      "Le Pouliguen" => "44510",
      "Les Moutiers-en-Retz" => "44760",
      "Ligné" => "44850",
      "Loireauxence" => "44370",
      "Louisfert" => "44110",
      "Lusanger" => "44590",
      "Machecoul" => "44270",
      "Malville" => "44260",
      "Mauves-sur-Loire" => "44470",
      "Mesquer" => "44420",
      "Missillac" => "44780",
      "Monnières" => "44690",
      "Montoir-de-Bretagne" => "44550",
      "Montoir-sur-Loire" => "44310",
      "Mouzillon" => "44330",
      "Nantes" => "44000",
      "Saint-Herblain" => "4480"
    ];

    $randomPostcode = new \Random\Randomizer();

    $city = $randomPostcode->pickArrayKeys($postCodes, 1)[0];

    $postcode = $postCodes[$city];

    $address = new Address();
    $address->setStreetAddress($this->faker->streetAddress());
    $address->setCity($city);
    $address->setPostcode($postcode);
    $address->setCustomer($customer);
    $manager->persist($address);
  }

  private function generateCustomers(mixed $manager): void
  {
    $this->faker = Factory::create();

    for ($i = 0; $i < 250; $i++) {
      $customer = new Customer();
      $customer->setfirstName($this->faker->firstName($gender = 'male' | 'female'));
      $customer->setLastname($this->faker->lastName());
      $manager->persist($customer);
      $this->generateAddress($manager, $customer);
    }
  }

  private function generateRoles(mixed $manager): void
  {
    $OPS = new Role();
    $OPS->setName('OPS');
    $AS = new Role();
    $AS->setName('AS');
    $manager->persist($OPS);
    $manager->persist($AS);

    $manager->flush();
  }

  private function generateAssociates(mixed $manager): void
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

  private function generatePackagings(mixed $manager): void
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

  private function generatePackages(mixed $manager, mixed $order)
  {

    $PackagingRepository = $manager->getRepository(Packaging::class);
    $packagings = $PackagingRepository->findAll();
    shuffle($packagings);

    $package = new Package();
    $package->setWeight(random_int(50, 2000));
    $package->setPackaging($packagings[0]);
    $package->setOrderId($order);
    $manager->persist($package);
  }

  private function generateOrders(mixed $manager): void
  {
    $CustomerRepository = $manager->getRepository(Customer::class);
    $AddressRepository = $manager->getRepository(Address::class);

    $customers = $CustomerRepository->findAll();

    foreach ($customers as $customer) {
      $order = new Order();
      $order->setCustomer($customer);

      $customerId = $customer->getId();

      $address = $AddressRepository->findOneBy(['customer' => $customerId]);

      $order->setAddress($address);
      $manager->persist($order);

      $this->generatePackages($manager, $order);
    }
  }

  private function generateDeliveryPeople(mixed $manager, $DeliveryCompany): void
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

  private function generateDeliveryCompany(mixed $manager): void
  {
    $companiyNames = ['ATA', 'CNPS', 'Coliwest', 'HTDS', 'RAIZ'];

    foreach ($companiyNames as $companiyName) {
      $DeliveryCompany = new DeliveryCompany();
      $DeliveryCompany->setName($companiyName);

      $manager->persist($DeliveryCompany);

      $this->generateDeliveryPeople($manager, $DeliveryCompany);
    }
  }

  private function generateStaggings(mixed $manager): void
  {
    $letters = ['A', 'B', 'C', 'D', 'E', 'G'];

    foreach ($letters as $letter) {
      $staggingNumber = 1;
      for ($i = 0; $i < 6; $i++) {
        $stagging = new Stagging();
        $stagging->setName($letter . '-' . $staggingNumber);
        $staggingNumber++;
        $manager->persist($stagging);
      }
    }
  }

  private function generateLocations(mixed $manager): void
  {
    $alleys = ['B', 'C'];
    $letters = ['A', 'B', 'C', 'D', 'E', 'G'];

    foreach ($alleys as $alley) {
      $alleyNumber = 1;
      for ($i = 0; $i < 52; $i++) {
        $alleyName = $alley . '-' . $alleyNumber;
        foreach ($letters as $letter) {
          $locationNumber = 1;
          for ($j = 0; $j < 2; $j++) {
            $location = new Location();
            $location->setName($alleyName . '-' . $letter . '-' . $locationNumber);
            $locationNumber++;
            $manager->persist($location);
          }
        }
        $alleyNumber++;
      }
    }
  }

  private function generateBags(mixed $manager): void
  {
    $colors = ['BLK', 'NVY', 'ORG', 'YLO', 'GRN'];

    foreach ($colors as $color) {
      $randomizer = new \Random\Randomizer();
      for ($i = 0; $i < 130; $i++) {
        $colorNumber = random_int(1, 9999);
        $bag = new Bag();
        $colorNumber_format = sprintf("%04d", $colorNumber);
        $bag->setName($color . '-' . $colorNumber_format);
        $bag->setDamaged($randomizer->nextFloat() < 0.1);
        $manager->persist($bag);
      }
    }
  }

  public function load(ObjectManager $manager): void
  {
    $this->generateDocks($manager);
    $this->generateTrucks($manager);
    $this->generateCustomers($manager);
    $this->generateRoles($manager);
    $this->generateAssociates($manager);
    $this->generatePackagings($manager);
    $this->generateOrders($manager);
    $this->generateDeliveryCompany($manager);
    $this->generateStaggings($manager);
    $this->generateLocations($manager);
    $this->generateBags($manager);

    $manager->flush();
  }
}
