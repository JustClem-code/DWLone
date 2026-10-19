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

  private function toArray(Location $location): array
  {
    return [
      'id' => $location->getId(),
      'name' => $location->getName(),
      'packages' => $location->getPackages() ? $this->transFormEntities($location->getPackages(), [$this->packageRepository, 'toArray']) : null,
      'bag' => $location->getBag() ? $location->getBag() : null,
    ];
  }

  private function toArrayBagOriented(Location $location): array
  {
    return [
      'id' => $location->getId(),
      'name' => $location->getName(),
      'bag' => $location->getBag() ? $this->bagArrayTransformer->toArray($location->getBag()) : null,
    ];
  }

  private function getPairKey(string $name): ?string
  {
    $base = strtok($name, '-');

    if ($base === false || $base === '') {
      return null;
    }

    if (!preg_match('/^(?<letter>[a-zA-Z]+)(?<num>\d+)$/', $base, $matches)) {
      return null;
    }

    $n = (int) $matches['num'];

    $pairBase = $n % 2 === 0 ? $n - 1 : $n;

    return sprintf('%s%d', $matches['letter'], $pairBase);
  }

  private function getPairName(string $name): string
  {
    if (!preg_match('/^(?<letter>[a-zA-Z]+)\s*-?\s*(?<num>\d+)$/', $name, $matches)) {
      throw new \InvalidArgumentException(sprintf('Invalid location name: "%s"', $name));
    }

    $letter = $matches['letter'];
    $n = (int) $matches['num'];

    return sprintf('%s%d & %s%d', $letter, $n, $letter, $n + 1);
  }

  private function reverseOrderOfLocation(iterable $locations): array
  {
    $len   = count($locations);
    $half  = intdiv($len, 2);

    $second = array_slice($locations, $half);
    $first  = array_slice($locations, 0, $half);

    return array_merge($second, $first);
  }

  private function groupByPair(iterable $aisles): array
  {
    $grouped = [];

    foreach ($aisles as $location) {
      $name = $location['name'];
      $key  = $this->getPairKey($name);

      if ($key === null) {
        $grouped['_invalid'][] = $location;
        continue;
      }

      $grouped[$key][] = $location;
    }

    return $grouped;
  }

  private function getPairLocations(iterable $grouped): array
  {
    $result = [];

    foreach ($grouped as $key => $locationsArray) {
      if ($key === '_invalid') {
        continue;
      }

      if (!preg_match('/^([a-zA-Z]+)\s*-?\s*(\d+)$/', $key, $matches)) {
        continue;
      }

      $letter = $matches[1];
      $aisleNumber = (int) $matches[2];

      if ($aisleNumber > 26) {
        $locationsArray = $this->reverseOrderOfLocation($locationsArray);
      }

      $result[] = [
        'id'        => $this->getPairName($key),
        'locations' => $locationsArray,
      ];
    }

    return $result;
  }

  private function indexLocationsByName(iterable $locations): array
  {
    $byKey = [];
    foreach ($locations as $loc) {
      $byKey[$loc['name']] = $loc;
    }
    return $byKey;
  }

  private function getAisleLetters(): array
  {
    return ['C', 'B'];
  }

  private function getBagLetters(): array
  {
    return ['A', 'B', 'C', 'D', 'E', 'G'];
  }

  private function getLettersForFloor(int $floor, bool $isFps): array
  {
    $bagLetters = $this->getBagLetters();
    $bagLettersReverse = $isFps ? $bagLetters : array_reverse($bagLetters);
    return $floor <= 26 ? $bagLettersReverse : $bagLetters;
  }

  private function getOrderSpecs(int $floor, bool $isFps): array
  {
    $orderSpecsPair = [['side' => '1'], ['side' => '2']];
    $orderSpecsOdd = [['side' => '2'], ['side' => '1']];

    if ($isFps && $floor > 26) {
      return $floor % 2 !== 0 ? $orderSpecsPair : $orderSpecsOdd;
    }

    return $floor % 2 === 0 ? $orderSpecsPair : $orderSpecsOdd;
  }

  private function floorOrdered(iterable $locations, bool $isFps = false): array
  {
    $byKey = $this->indexLocationsByName($locations);
    $result = [[], [], [], []];
    $indexGlobal = 0;

    foreach ($this->getAisleLetters() as $aisleLet) {
      for ($floor = 1; $floor <= 52; $floor++) {
        $letters = $this->getLettersForFloor($floor, $isFps);
        $specs = $this->getOrderSpecs($floor, $isFps);

        foreach ($specs as $spec) {
          foreach ($letters as $letter) {
            $key = sprintf('%s%d-%s%s', $aisleLet, $floor, $letter, $spec['side']);



            if (isset($byKey[$key])) {
              $arrayIndex = intdiv($indexGlobal, 312);
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

  public function mapAllInPair(iterable $locations): array
  {
    $finalResult = [[], [], [], []];
    $index = 0;

    foreach ($locations as $aisles) {

      $grouped = $this->groupByPair($aisles);



      $result = $this->getPairLocations($grouped);

      $finalResult[$index] = $result;
      $index++;
    }

    return $finalResult;
  }

  public function transformAllInPair(): array
  {
    return $this->mapAllInPair($this->floorOrdered($this->transFormEntities($this->locationRepository->findAll(), [$this, 'toArray']), true));
  }

  public function transformAllInPairLight(): array
  {
    return $this->mapAllInPair($this->floorOrdered($this->transFormEntities($this->locationRepository->findAll(), [$this->locationRepository, 'toArray']), true));
  }

  public function transformAllBagOriented(): array
  {
    return $this->floorOrdered($this->transFormEntities($this->locationRepository->findAll(), [$this, 'toArrayBagOriented']));
  }
}
