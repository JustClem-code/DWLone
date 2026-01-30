<?php

namespace App\Service;

use App\Entity\Package;
use App\Repository\BagRepository;
use App\Repository\LocationRepository;
use App\Repository\PackageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class SetPackageLocationService
{
  public function __construct(
    private EntityManagerInterface $entityManager,
    private PackageRepository $packageRepository,
    private LocationRepository $locationRepository,
    private BagRepository $bagRepository,
    private Security $security
  ) {}

  public function setPackageLocation(Package $package): Package
  {

    if (!$package->getLocation()) {

      $location = $this->packageRepository->findLocationBySamePostcode($package);

      if (!$location) {
        $location = $this->locationRepository->findRandomWithoutPackage();
      }

      if (!$location->getBag()) {
        $randomBag = $this->bagRepository->findRandomWithoutLocation();
        $location->setBag($randomBag);
      }

      $package->setLocation($location);
      $package->setBag($location->getBag());

      $this->entityManager->flush();
    }

    return $package;
  }

  public function setPackageUserStow(Package $package): Package
  {
    $package->setUserStow($this->security->getUser());
    
    $this->entityManager->flush();

    return $package;
  }

  public function resetLocationsBagsPackages(): void
  {
    $packagesWithLocation = $this->packageRepository->findAllHasLocation();

    foreach ($packagesWithLocation as $package) {
      $package->setLocation(null);
      $package->setBag(null);
      $package->setUserStow(null);
    }

    $bagsWithLocation = $this->bagRepository->findAllHasLocation();

    foreach ($bagsWithLocation as $bag) {
      $bag->setLocation(null);
    }

    $this->entityManager->flush();
  }
}
