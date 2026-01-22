<?php

namespace App\Controller;

use App\Repository\Trait\RepositoryTrait;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Package;

use App\Repository\PackageRepository;
use App\Repository\BagRepository;
use App\Repository\PalletRepository;

use App\Service\SetPackageLocationService;

final class InductionController extends AbstractController
{
  use RepositoryTrait;

  public function __construct(private SetPackageLocationService $setPackageLocationService) {}

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
    int $id,
  ): Response {

    $package = $entityManager->getRepository(Package::class)->find($id);

    if (!$package) {
      throw $this->createNotFoundException(
        'No package found for id ' . $id
      );
    }

    $data = $this->setPackageLocationService->setPackageLocation($package);

    return $this->json($packageRepository->toArray($data));
  }
}
