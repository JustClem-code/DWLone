<?php

namespace App\Service;

use App\Repository\Trait\RepositoryTrait;

use App\Entity\Bag;
use App\Repository\PackageRepository;
use App\Repository\LocationRepository;

class BagArrayTransformer
{
  use RepositoryTrait;

  public function __construct(private PackageRepository $packageRepository, private LocationRepository $locationRepository) {}

  public function toArray(Bag $bag): array
  {
    return [
      'id' => $bag->getId(),
      'name' => $bag->getName(),
      'getRoad' => $bag->getRoad(),
      'packages' => $bag->getLocation()->getPackages() ? $this->transFormEntities($bag->getLocation()->getPackages(), [$this->packageRepository, 'toArrayBagOriented']) : null,
    ];
  }
}
