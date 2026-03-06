<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

use App\Repository\PackageRepository;
use App\Repository\PalletRepository;

use App\Service\LocationArrayTransformerService;
use App\Service\SetPackageLocationService;

final class DashboardController extends AbstractController
{
  public function __construct(
    private LocationArrayTransformerService $locationArrayTransformerService,
    private SetPackageLocationService $setPackageLocationService,
    private PackageRepository $packageRepository
  ) {}

  #[Route('/', name: 'app_dashboard')]
  public function index(): Response
  {
    return $this->render('dashboard/index.html.twig', [
      'controller_name' => 'DashboardController',
    ]);
  }

  private function packagesStats(): array
  {
    return [
      'packagesWithoutLocation' => $this->packageRepository->transformCollection(
        $this->packageRepository->findAllWithoutLocationFromPalletsWithUser()
      ),
      'packagesWithLocationNotStowed' => $this->packageRepository->transformCollection(
        $this->packageRepository->findAllWithLocationAndNotStowed()
      ),
      'packagesWithLocation' => $this->packageRepository->transformCollection(
        $this->packageRepository->findAllHasLocation()
      ),
      'packagesWithLocationAndStowed' => $this->packageRepository->transformCollection(
        $this->packageRepository->findAllWithLocationAndStowed()
      )
    ];
  }

  #[Route('/getAllPackagesOnFloor', name: 'get_all_packages_on_floor', methods: ['GET'])]
  public function getAllPackagesOnFloor(PackageRepository $packageRepository): Response
  {
    return $this->json([
      'allPackages' => $packageRepository->transformCollection($packageRepository->findAllFromPalletsWithUser()),
    ]);
  }

  #[Route('/getPackagesStats', name: 'get_packages_stats', methods: ['GET'])]
  public function getPackagesStats(): Response
  {
    return $this->json($this->packagesStats());
  }

  #[Route('/getBagsInLocations', name: 'get_bags_in_locations_list', methods: ['GET'])]
  public function getBagsInLocations(): Response
  {
    return $this->json($this->locationArrayTransformerService->transformAllBagOriented());
  }

  private function buildLocationsResponse(): Response
  {
    return $this->json([
      'locations' => $this->locationArrayTransformerService->transformAllBagOriented()
    ] + $this->packagesStats());
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

    return $this->buildLocationsResponse();
  }

  #[Route('/hardResetLocationsBagsPackages', name: 'hard_reset_locations_bags_packages', methods: ['POST'])]
  public function hardResetLocationsBagsPackages(): Response
  {
    $this->setPackageLocationService->resetLocationsBagsPackages();

    return $this->buildLocationsResponse();
  }
}
