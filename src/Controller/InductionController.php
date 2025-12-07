<?php

namespace App\Controller;

use App\Controller\Trait\RepositoryTrait;
use App\Entity\Package;
use App\Entity\Location;
use App\Entity\Bag;
use App\Repository\PackageRepository;
use App\Repository\LocationRepository;
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

  #[Route('/setLocation/{id}', name: 'set_location')]
  public function setLocation(
    Request $request,
    EntityManagerInterface $entityManager,
    int $id,
    PackageRepository $packageRepository,
    LocationRepository $locationRepository,
  ): Response {
    $package = $entityManager->getRepository(Package::class)->find($id);

    if (!$package) {
      throw $this->createNotFoundException(
        'No pallet found for id ' . $id
      );
    }

    $location = $packageRepository->findLocationBySamePostcode($package);

    if ($location) {
      if (!$location->getBag()) {
        $randomBag = $entityManager->getRepository(Bag::class)->findRandomWithoutLocation();
        $location->setBag($randomBag);
      }
      $package->setLocation($location);
      $package->setBag($location->getBag());
    } else {
      $randomLocation = $entityManager->getRepository(Location::class)->findRandomWithoutPackage();
      if (!$randomLocation->getBag()) {
        $randomBag = $entityManager->getRepository(Bag::class)->findRandomWithoutLocation();
        $randomLocation->setBag($randomBag);
      }
      $package->setLocation($randomLocation);
      $package->setBag($randomLocation->getBag());
    }

    $entityManager->flush();

    $alreadyLocated = $packageRepository->transformCollection($packageRepository->findAllHasLocation());

    dump($alreadyLocated);

    return $this->json($packageRepository->toArray($package));
  }
}
