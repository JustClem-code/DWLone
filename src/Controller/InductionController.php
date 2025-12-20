<?php

namespace App\Controller;

use App\Repository\Trait\RepositoryTrait;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Package;

use App\Repository\PackageRepository;
use App\Repository\LocationRepository;
use App\Repository\BagRepository;
use App\Repository\PalletRepository;

final class InductionController extends AbstractController
{
  use RepositoryTrait;

  #[Route('/warehouse/induction', name: 'app_induction')]
  public function index(): Response
  {
    return $this->render('induction/index.html.twig', [
      'controller_name' => 'InductionController',
    ]);
  }

  #[Route('/getpalletsonfloorwithpackages', name: 'get_pallets_on_floor_with_packages_list', methods: ['GET'])]
  public function getPalletsOnFloorWithPackages(PalletRepository $repository): Response
  {
    return $this->json($repository->findAllHasUserAndPackageWithoutLocation());
  }

  #[Route('/setLocation/{id}', name: 'set_location', methods: ['POST'])]
  public function setLocation(
    Package $package,
    EntityManagerInterface $entityManager,
    PackageRepository $packageRepository,
    LocationRepository $locationRepository,
    BagRepository $bagRepository,
    int $id,
  ): Response {
    $package = $entityManager->getRepository(Package::class)->find($id);

    if (!$package) {
      throw $this->createNotFoundException(
        'No package found for id ' . $id
      );
    }

    if (!$package->getLocation()) {

      $location = $packageRepository->findLocationBySamePostcode($package);

      if (!$location) {
        $location = $locationRepository->findRandomWithoutPackage();
      }

      if (!$location->getBag()) {
        $randomBag = $bagRepository->findRandomWithoutLocation();
        $location->setBag($randomBag);
      }

      $package->setLocation($location);
      $package->setBag($location->getBag());

      $entityManager->flush();
    }

    return $this->json($packageRepository->toArray($package));
  }

  #[Route('/resetLocationsBagsPackages', name: 'reset_locations_bags_packages', methods: ['POST'])]
  public function resetLocationsBagsPackages(
    EntityManagerInterface $entityManager,
    PackageRepository $packageRepository,
    BagRepository $bagRepository,
    PalletRepository $palletRepository
  ): Response {

    $packagesWithLocation = $packageRepository->findAllHasLocation();

    foreach ($packagesWithLocation as $package) {
      $package->setLocation(null);
      $package->setBag(null);
    }

    $bagsWithLocation = $bagRepository->findAllHasLocation();

    foreach ($bagsWithLocation as $bag) {
      $bag->setLocation(null);
    }

    $entityManager->flush();

    return $this->getPalletsOnFloorWithPackages($palletRepository);
  }
}
