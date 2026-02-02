<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

use App\Repository\PackageRepository;
use App\Repository\PalletRepository;

use App\Service\LocationArrayTransformer;
use App\Service\SetPackageLocationService;

final class DashboardController extends AbstractController
{
  public function __construct(private LocationArrayTransformer $locationArrayTransformer, private SetPackageLocationService $setPackageLocationService) {}

  #[Route('/', name: 'app_dashboard')]
  public function index(): Response
  {
    return $this->render('dashboard/index.html.twig', [
      'controller_name' => 'DashboardController',
    ]);
  }

  #[Route('/getAllPackagesOnFloor', name: 'get_all_packages_on_floor', methods: ['GET'])]
  public function getAllPackagesOnFloor(PackageRepository $packageRepository): Response
  {
    return $this->json([
      'allPackages' => $packageRepository->transformCollection($packageRepository->findAllFromPalletsWithUser()),
      'packagesWithoutLocation' => $packageRepository->transformCollection($packageRepository->findAllWithoutLocationFromPalletsWithUser()),
      'packagesWithLocationNotStowed' => $packageRepository->transformCollection($packageRepository->findAllWithLocationAndNotStowed()),

    ]);
  }

  #[Route('/getBagsInLocations', name: 'get_bags_in_locations_list', methods: ['GET'])]
  public function getBagsInLocations(): Response
  {
    return $this->json($this->locationArrayTransformer->transformAllBagOriented());
  }

  #[Route('/automaticInductAndStow', name: 'automatic_induct_and_stow', methods: ['POST'])]
  public function automaticInductAndStow(PackageRepository $packageRepository, Request $request): Response
  {
    $induct = $request->getPayload()->get('induct');
    $stow = $request->getPayload()->get('stow');

    if ($induct) {
      $packages = $packageRepository->findAllWithoutLocationFromPalletsWithUser();

      foreach ($packages as $package) {
        $this->setPackageLocationService->setPackageLocation($package);
      }
    }

    if ($stow) {
      $packages = $packageRepository->findAllWithLocationAndNotStowed();

      foreach ($packages as $package) {
        $this->setPackageLocationService->setPackageUserStow($package);
      }
    }

    return $this->json(
      [
        'locations' => $this->locationArrayTransformer->transformAllBagOriented(),
        'packagesWithoutLocation' => $packageRepository->transformCollection($packageRepository->findAllWithoutLocationFromPalletsWithUser()),
        'packagesWithLocationNotStowed' => $packageRepository->transformCollection($packageRepository->findAllWithLocationAndNotStowed()),
      ]
    );
  }

  // Not Used
  /* #[Route('/getPackagesNotStowed', name: 'get_packages_not_stowed', methods: ['Get'])]
  public function getPackagesNotStowed(PackageRepository $packageRepository): Response
  {
    return $this->json($packageRepository->transformCollection($packageRepository->findAllWithLocationAndNotStowed()));
  } */

  #[Route('/hardResetLocationsBagsPackages', name: 'hard_reset_locations_bags_packages', methods: ['POST'])]
  public function hardResetLocationsBagsPackages(PackageRepository $packageRepository): Response
  {
    $this->setPackageLocationService->resetLocationsBagsPackages();

    return $this->json(
      [
        'locations' => $this->locationArrayTransformer->transformAllBagOriented(),
        'packagesWithoutLocation' => $packageRepository->transformCollection($packageRepository->findAllWithoutLocationFromPalletsWithUser()),
        'packagesWithLocationNotStowed' => $packageRepository->transformCollection($packageRepository->findAllWithLocationAndNotStowed()),
      ]
    );
  }
}
