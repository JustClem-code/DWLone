<?php

namespace App\Service;

use App\Repository\Trait\RepositoryTrait;

use App\Entity\Location;
use App\Repository\PackageRepository;

class LocationArrayTransformer
{
  use RepositoryTrait;

  public function __construct(private PackageRepository $packageRepository) {}

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

    return $grouped;
  }
}
