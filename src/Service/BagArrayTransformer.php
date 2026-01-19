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

  private function computeTotalWeight(iterable $packages)
  {
    $totalWeight = 0;
    foreach ($packages as $pkg) {
      $totalWeight += $pkg['totalWeight'];
    }
    return $totalWeight;
  }

  public function toArray(Bag $bag): array
  {
    $packages = $bag->getLocation()->getPackages() ?
      $this->transFormEntities(
        $bag->getLocation()->getPackages(),
        [$this->packageRepository, 'toArrayBagOriented']
      ) : null;

    return [
      'id' => $bag->getId(),
      'name' => $bag->getName(),
      'getRoad' => $bag->getRoad(),
      'locationName' => $bag->getLocation() ? $bag->getLocation()->getName() : null,
      'packages' => $packages,
      'totalBagWeight' => $this->computeTotalWeight($packages),
    ];
  }
}
