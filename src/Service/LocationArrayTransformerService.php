<?php

namespace App\Service;

use App\Repository\Trait\RepositoryTrait;

use App\Entity\Location;
use App\Repository\LocationRepository;
use App\Repository\PackageRepository;
use App\Service\BagArrayTransformer;

class LocationArrayTransformerService
{
  use RepositoryTrait;

  public function __construct(private LocationRepository $locationRepository, private PackageRepository $packageRepository, private BagArrayTransformer $bagArrayTransformer) {}

  private function getPairKey(string $name): ?string
  {
    $parts = explode('-', $name);

    if (!isset($parts[1]) || !ctype_digit($parts[1])) {
      return null;
    }

    $n = (int) $parts[1];

    $pairBase = $n % 2 === 0 ? $n - 1 : $n;

    $parts[1] = (string) $pairBase;

    return implode('-', array_slice($parts, 0, 2));
  }

  private function getPairName(string $name): string
  {
    [$letter, $num] = explode('-', $name);
    $n = (int) $num;
    return sprintf('%s%d & %s%d', $letter, $n, $letter, $n + 1);
  }


  public function toArray(Location $location): array
  {
    return [
      'id' => $location->getId(),
      'name' => $location->getName(),
      'packages' => $location->getPackages() ? $this->transFormEntities($location->getPackages(), [$this->packageRepository, 'toArray']) : null,
    ];
  }

  public function transformAll(iterable $locations): array
  {
    $grouped = [];

    foreach ($locations as $location) {
      $name = $location->getName();
      $key  = $this->getPairKey($name);

      if ($key === null) {
        $grouped['_invalid'][] = $location;
        continue;
      }

      $grouped[$key][] = $this->toArray($location);
    }

    $result = [];
    foreach ($grouped as $key => $locationsArray) {
      // si tu veux ignorer les invalides :
      if ($key === '_invalid') {
        continue;
      }

      $result[] = [
        'id'      => $this->getPairName($key),
        'locations' => $locationsArray,
      ];
    }

    return $result;
  }


  private function toArrayBagOriented(Location $location): array
  {
    return [
      'id' => $location->getId(),
      'name' => $location->getName(),
      'bag' => $location->getBag() ? $this->bagArrayTransformer->toArray($location->getBag()) : null,
    ];
  }


  private function floorOrdered($locations): array
  {

    // 1. Indexer par name (équivalent du Map in JS)
    $byKey = [];
    foreach ($locations as $loc) {
      // si c'est un objet
      // $byKey[$loc->name] = $loc;
      // si c'est un array, utiliser:
      $byKey[$loc['name']] = $loc;
    }

    // 2. Préparer la structure de résultat [[], [], [], []]
    $result = [[], [], [], []];

    $aisleLetters      = ['C', 'B'];
    $bagLetters        = ['A', 'B', 'C', 'D', 'E', 'G'];
    $bagLettersReverse = array_reverse($bagLetters);
    $orderSpecsPair    = [['side' => '1'], ['side' => '2']];
    $orderSpecsOdd     = [['side' => '2'], ['side' => '1']];

    $indexGlobal = 0;

    foreach ($aisleLetters as $aisleLet) {
      for ($floor = 1; $floor <= 52; $floor++) {
        $letters = $floor <= 26 ? $bagLettersReverse : $bagLetters;
        $specs   = $floor % 2 === 0 ? $orderSpecsPair : $orderSpecsOdd;

        foreach ($specs as $spec) {
          foreach ($letters as $letter) {
            $key = sprintf('%s-%d-%s-%s', $aisleLet, $floor, $letter, $spec['side']);

            if (isset($byKey[$key])) {
              // même règle de découpage : 312 par sous‑tableau, max 4
              $arrayIndex = intdiv($indexGlobal, 312); // PHP 7+
              if ($arrayIndex < 4) {
                $result[$arrayIndex][] = $byKey[$key];
              }
            }

            $indexGlobal++;
          }
        }
      }
    }

    return $result;
  }

  public function transformAllBagOriented(): array
  {
    return $this->floorOrdered($this->transFormEntities($this->locationRepository->findAll(), [$this, 'toArrayBagOriented']));
  }
}
