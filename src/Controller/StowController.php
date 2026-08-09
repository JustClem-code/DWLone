<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use App\Service\LocationArrayTransformerService;
use App\Service\SetPackageLocationService;

use App\Repository\PackageRepository;

final class StowController extends AbstractController
{
  public function __construct(
    private LocationArrayTransformerService $locationArrayTransformerService,
    private SetPackageLocationService $setPackageLocationService,
    private PackageRepository $packageRepository
  ) {}

  #[Route('/warehouse/stow', name: 'app_stow')]
  public function index(): Response
  {
    return $this->render('stow/index.html.twig', [
      'controller_name' => 'StowController',
    ]);
  }

  #[Route('/getlocations', name: 'get_locations_list', methods: ['GET'])]
  public function getLocations(): Response
  {
    return $this->json($this->locationArrayTransformerService->transformAllInPair());
  }

  #[Route('/setUserStow/{id}', name: 'set_user_stow', methods: ['POST'])]
  public function setUserStow(
    int $id,
  ): Response {
    $package = $this->packageRepository->find($id);

    if (!$package) {
      return $this->json(['error' => 'No package found for id ' . $id], 404);
    }

    $package = $this->setPackageLocationService->setPackageUserStow($package);

    return $this->json($this->packageRepository->toArray($package));
  }
}
