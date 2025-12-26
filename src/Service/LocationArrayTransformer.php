<?php

namespace App\Service;

use App\Repository\Trait\RepositoryTrait;

use App\Entity\Location;
use App\Repository\PackageRepository;

class LocationArrayTransformer
{
  use RepositoryTrait;

  public function __construct(private PackageRepository $packageRepository) {}

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
    return array_map([$this, 'toArray'], $locations);
  }
}
