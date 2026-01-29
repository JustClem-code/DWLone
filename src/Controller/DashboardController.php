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
      # code...
    }

    return $this->json($this->locationArrayTransformer->transformAllBagOriented());
  }

  #[Route('/hardResetLocationsBagsPackages', name: 'hard_reset_locations_bags_packages', methods: ['POST'])]
  public function hardResetLocationsBagsPackages(): Response
  {
    $this->setPackageLocationService->resetLocationsBagsPackages();

    return $this->json($this->locationArrayTransformer->transformAllBagOriented());
  }
}
