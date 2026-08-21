<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

use App\Repository\TruckRepository;
use App\Repository\DockRepository;
use App\Repository\PalletRepository;
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

use Symfony\Bundle\SecurityBundle\Security;

final class DashboardController extends AbstractController
{
  public function __construct(
    private Security $security,
    private TruckRepository $truckRepository,
    private DockRepository $dockRepository,
    private PalletRepository $palletRepository,
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

  // Yard truck and unloading

  private function yardTruckStats(): array
  {
    return [
      'expectedTrucks' => count(
        $this->truckRepository->findAll()
      ),
      'expectedPallets' => count(
        $this->palletRepository->findAll()
      ),
      'waitingTrucks' => count(
        $this->truckRepository->findAllWithoutDock()
      ),
      'processedTrucks' => count(
        $this->truckRepository->findAllOut()
      ) + count(
        $this->truckRepository->findAllWithDock()
      ),
      'waitingPallets' => count(
        $this->palletRepository->findAllWithoutUser()
      ),
      'waitingPalletsDocked' => count(
        $this->palletRepository->findAllWithoutUserInTruckDocked()
      ),
      'unloadingPallets' => count(
        $this->palletRepository->findAllWithUser()
      ),
      'unloadingPalletsClean' => count(
        $this->palletRepository->findAllWithUserAndWithoutPackageInducted()
      ),
    ];
  }

  private function dockingAllTrucks(): void
  {
    $trucks = $this->truckRepository->findAllWithoutDock();

    foreach ($trucks as $truck) {
      $dock = $this->dockRepository->findFirstWithNoTruck();

      $truck->dockTruck($dock, $this->security->getUser());

      $this->entityManager->flush();
    }
  }

  private function unloadingAllPallets(): void
  {
    $pallets = $this->palletRepository->findAllWithoutUserInTruckDocked();

    foreach ($pallets as $pallet) {
      $pallet->setUserId($this->security->getUser());
    }

    $this->entityManager->flush();
  }

  #[Route('/getyardtruckstats', name: 'get_yard_truck_stats', methods: ['GET'])]
  public function getYardTruckStats(): Response
  {
    return $this->json($this->yardTruckStats());
  }

  #[Route('/automaticdockingtrucks', name: 'automatic_docking_trucks', methods: ['POST'])]
  public function automaticDockingTrucks(): Response
  {
    $this->dockingAllTrucks();

    return $this->json($this->yardTruckStats());
  }

  #[Route('/automaticunloadingpallets', name: 'automatic_unloading_pallets', methods: ['POST'])]
  public function automaticUnloadingPallets(): Response
  {
    $this->unloadingAllPallets();

    return $this->json($this->yardTruckStats());
  }

  #[Route('/autodockingandunloading', name: 'auto_docking_and_unloading', methods: ['POST'])]
  public function autoDockingAndUnloading(): Response
  {
    $this->dockingAllTrucks();
    $this->unloadingAllPallets();

    return $this->json($this->yardTruckStats());
  }

  #[Route('/resetdockingandunloading', name: 'reset_docking_and_unloading', methods: ['POST'])]
  public function resetDockingAndUnloading(): Response
  {
    $pallets = $this->palletRepository->findAllWithUserAndWithoutPackageInducted();

    foreach ($pallets as $pallet) {
      $pallet->setUserId(null);
      $this->entityManager->flush();
    }

    $trucks = $this->truckRepository->findAll();

    foreach ($trucks as $truck) {
      $truck->resetTruck();
      $this->entityManager->flush();
    }

    return $this->json($this->yardTruckStats());
  }

  // Induct and Stow

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

  // Picking

  private function resetPicking(RoadPart $roadPart): void
  {
    $roadPart->getCart()?->setRoadPart(null);
    foreach ($roadPart->getBags() as $bag) {
      $bag->setPicked(false);
    }
    $roadPart->resetPickingState();
  }

  private function someBagPicked(iterable $bags): bool
  {
    return !empty(iterator_to_array($bags)) && array_any(
      iterator_to_array($bags),
      fn($bag) => $bag->isPicked()
    );
  }

  #[Route('/resetRoadPart/{id}', name: 'reset_road_part')]
  public function resetRoadPart(int $id): Response
  {
    $roadPart = $this->roadPartRepository->find($id);

    if (!$roadPart) {
      return $this->json(['error' => 'No road part available'], 404);
    }

    if ($this->someBagPicked($roadPart->getBags())) {
      return $this->json(['error' => 'Some bags is picked'], 404);
    }

    $this->resetPicking($roadPart);

    $this->entityManager->flush();

    return $this->json($this->roadPartRepository->toArray($roadPart));
  }

  #[Route('/hardResetPicking', name: 'hard_reset_picking', methods: ['POST'])]
  public function hardResetPicking(): Response
  {
    $roadParts = $this->roadPartRepository->findAllWithUser();

    foreach ($roadParts as $roadPart) {
      $this->resetPicking($roadPart);
    }

    $this->entityManager->flush();

    return $this->getAllRoadParts();
  }

  #[Route('/deleteAllRoads', name: 'delete_all_roads', methods: ['POST'])]
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

    return $this->getAllRoadParts();
  }

  #[Route('/getAllRoads', name: 'get_all_roads', methods: ['GET'])]
  public function getAllRoads(): Response
  {
    return $this->json($this->roadRepository->transformAllOrderedByName());
  }

  #[Route('/getAllRoadParts', name: 'get_all_road_parts', methods: ['GET'])]
  public function getAllRoadParts(): Response
  {
    return $this->json($this->roadPartRepository->transformAllOrderedByName());
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

  #[Route('/generateAllRoads', name: 'generate_all_roads', methods: ['POST'])]
  public function generateAllRoads(): Response
  {
    $bags = $this->getAllBagsWithPackages();

    foreach ($bags as $bag) {
      $road = $this->getOrCreateRoad($bag);

      $roadPart = $this->getRoadPart($road);
      $roadPart->addBag($bag);

      $this->entityManager->flush();
    }

    return $this->getAllRoadParts();
  }
}
