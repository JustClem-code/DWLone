<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

use App\Repository\PackageRepository;
use App\Repository\RoadRepository;
use App\Repository\PostcodesRepository;
use App\Repository\RoadPartRepository;
use App\Repository\BagRepository;
use App\Repository\StaggingRepository;


use App\Service\LocationArrayTransformerService;
use App\Service\SetPackageLocationService;

use App\Entity\Road;
use App\Entity\RoadPart;
use App\Entity\Bag;


final class DashboardController extends AbstractController
{
  public function __construct(
    private LocationArrayTransformerService $locationArrayTransformerService,
    private SetPackageLocationService $setPackageLocationService,
    private PackageRepository $packageRepository,
    private RoadRepository $roadRepository,
    private PostcodesRepository $postcodesRepository,
    private RoadPartRepository $roadPartRepository,
    private BagRepository $bagRepository,
    private StaggingRepository $staggingRepository,
    private EntityManagerInterface $entityManager,
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
      'packagesWithoutLocationNumber' => count(
        $this->packageRepository->findAllWithoutLocationFromPalletsWithUser()
      ),
      'packagesWithLocationNotStowedNumber' => count(
        $this->packageRepository->findAllWithLocationAndNotStowed()
      ),
      'packagesWithLocationNumber' => count(
        $this->packageRepository->findAllHasLocation()
      ),
      'packagesWithLocationAndStowedNumber' => count(
        $this->packageRepository->findAllWithLocationAndStowed()
      ),
    ];
  }

  #[Route('/getAllPackagesOnFloor', name: 'get_all_packages_on_floor', methods: ['GET'])]
  public function getAllPackagesOnFloor(): Response
  {
    return $this->json([
      'allPackagesNumber' => count($this->packageRepository->findAllFromPalletsWithUser()),
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
    ] + ['allPackagesStats' => $this->packagesStats()]);
  }

  #[Route('/automaticInductAndStow', name: 'automatic_induct_and_stow', methods: ['POST'])]
  public function automaticInductAndStow(Request $request): Response
  {
    $induct = $request->getPayload()->get('induct');
    $stow = $request->getPayload()->get('stow');

    if ($induct) {
      $packages = $this->packageRepository->findAllWithoutLocationFromPalletsWithUser();

      foreach ($packages as $package) {
        $this->setPackageLocationService->setPackageLocation($package);
      }
    }

    if ($stow) {
      $packages = $this->packageRepository->findAllWithLocationAndNotStowed();

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

  private function resetPicking(RoadPart $roadPart): void
  {
    $roadPart->getCart()?->setRoadPart(null);
    foreach ($roadPart->getBags() as $bag) {
      $bag->setPicked(false);
    }
    $roadPart->resetPickingState();
  }

  #[Route('/hardResetPicking', name: 'hard_reset_picking', methods: ['POST'])]
  public function hardResetPicking(): Response
  {
    $roadParts = $this->roadPartRepository->findAllWithUser();

    foreach ($roadParts as $roadPart) {
      $this->resetPicking($roadPart);
    }

    $this->entityManager->flush();

    return $this->json($this->roadPartRepository->transformAll($roadParts));
  }

  #[Route('/deleteAllRoads', name: 'delete_all_roads', methods: ['GET'])]
  public function deleteAllRoads(): Response
  {
    $allRoads = $this->roadRepository->findAll();

    foreach ($allRoads as $road) {
      foreach ($road->getRoadParts() as $roadPart) {
        $this->resetPicking($roadPart);
        $this->entityManager->remove($roadPart);
      }
      $this->entityManager->remove($road);
    }

    $this->entityManager->flush();

    return $this->getAllRoads();
  }

  #[Route('/getAllRoads', name: 'get_all_roads', methods: ['GET'])]
  public function getAllRoads(): Response
  {
    $allRoads = $this->roadRepository->findAllOrderedByName();

    return $this->json($this->roadRepository->transformAll($allRoads));
  }

  private function getAllBagsWithPackages(): array
  {
    return $this->bagRepository->findAllHasLocationAndPackages() ?? [];
  }

  private function createRoadPart(Road $road, int $partNumber): RoadPart
  {
    $roadPart = new RoadPart();
    $road->addRoadPart($roadPart);
    $roadPart->setNumber($partNumber);
    $roadPart->setStagged(false);
    $this->entityManager->persist($roadPart);

    return $roadPart;
  }

  private function getRoadPart(Road $road): RoadPart
  {
    $roadPart = $this->roadPartRepository->findOneBy(
      ['road' => $road],
      ['number' => 'DESC']
    );

    if (!$roadPart) {
      $roadPart = $this->createRoadPart($road, 1);
    }

    if (count($roadPart->getBags()) > 6) {
      $incrementedNumber = $roadPart->getNumber() + 1;
      $roadPart = $this->createRoadPart($road, $incrementedNumber);
    }

    return $roadPart;
  }

  private function getOrCreateRoad(Bag $bag): Road
  {
    $postcode = $this->bagRepository->findBagPostcode($bag);

    $postcodeEntity = $this->postcodesRepository->findOneBy(['name' => $postcode]);
    $groupName = $postcodeEntity?->getGroupPostcodes()?->getName();

    $road = $this->roadRepository->findOneBy(['name' => $groupName]);

    if (!$road) {
      $staggings = $this->staggingRepository->findWithoutRoad();
      shuffle($staggings);
      $road = new Road();
      $road->setName($groupName);
      $road->setStagging($staggings[0]);
      $this->entityManager->persist($road);
    }

    return $road;
  }

  #[Route('/generateAllRoads', name: 'generate_all_roads', methods: ['GET'])]
  public function generateAllRoads(): Response
  {
    $bags = $this->getAllBagsWithPackages();

    foreach ($bags as $bag) {
      $road = $this->getOrCreateRoad($bag);

      $roadPart = $this->getRoadPart($road);
      $roadPart->addBag($bag);

      $this->entityManager->flush();
    }

    return $this->getAllRoads();
  }



  // Route pour avoir toutes les roads avec le user et les timer pour pouvoir les filtrer

  // Route pour reset les roads

  // Route pour reset un road en cour seulement si aucun sac n'est pické donc seulement le user et le cart
}
