<?php

namespace App\Controller;

use App\Repository\Trait\RepositoryTrait;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use App\Repository\PackageRepository;
use App\Repository\PalletRepository;

use App\Service\SetPackageLocationService;

final class InductionController extends AbstractController
{
  use RepositoryTrait;

  public function __construct(
    private SetPackageLocationService $setPackageLocationService,
    private PackageRepository $packageRepository,
    private PalletRepository $palletRepository
  ) {}

  #[Route('/warehouse/induction', name: 'app_induction')]
  public function index(): Response
  {
    return $this->render('induction/index.html.twig', [
      'controller_name' => 'InductionController',
    ]);
  }

  #[Route('/getpalletsonfloorwithpackages', name: 'get_pallets_on_floor_with_packages_list', methods: ['GET'])]
  public function getPalletsOnFloorWithPackages(): Response
  {
    return $this->json($this->palletRepository->transformAllHasUserAndPackageWithoutLocation());
  }

  #[Route('/setLocation/{id}', name: 'set_location', methods: ['POST'])]
  public function setLocation(
    int $id,
  ): Response {

    $package = $this->packageRepository->find($id);

    if (!$package) {
      return $this->json(['error' => 'No package found for id ' . $id], 404);
    }

    $data = $this->setPackageLocationService->setPackageLocation($package);

    return $this->json($this->packageRepository->toArray($data));
  }
}
