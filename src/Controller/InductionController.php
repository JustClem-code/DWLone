<?php

namespace App\Controller;

use App\Controller\Trait\RepositoryTrait;
use App\Entity\Package;
use App\Entity\Location;
use App\Entity\Bag;
use App\Repository\PackageRepository;
use App\Repository\LocationRepository;
use App\Repository\BagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

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

    $alreadyLocated = $packageRepository->transformCollection(
      $packageRepository->findAllHasLocation()
    );

    return $this->json($packageRepository->toArray($package));
  }

  #[Route('/resetLocationsBagsPackages', name: 'reset_locations_bags_packages', methods: ['POST'])]
  public function resetLocationsBagsPackages(
    EntityManagerInterface $entityManager,
    PackageRepository $packageRepository,
    BagRepository $bagRepository,
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

    return $this->json($bagsWithLocation);
  }
}
