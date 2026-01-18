<?php

namespace App\Service;

use App\Repository\Trait\RepositoryTrait;

use App\Entity\Location;
use App\Repository\LocationRepository;
use App\Repository\PackageRepository;
use App\Service\BagArrayTransformer;

class LocationArrayTransformer
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

  public function transformAllBagOriented(): array
  {
    return $this->transFormEntities($this->locationRepository->findAll(), [$this, 'toArrayBagOriented']);
  }
}
