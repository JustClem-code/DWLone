<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

use App\Repository\BagRepository;
use App\Repository\RoadRepository;
use App\Repository\RoadPartRepository;
use App\Repository\PostcodesRepository;
use App\Repository\StaggingRepository;
use Symfony\Bundle\SecurityBundle\Security;

use App\Service\LocationArrayTransformerService;

use App\Entity\Road;
use App\Entity\RoadPart;

final class PickingController extends AbstractController
{
  public function __construct(
    private BagRepository $bagRepository,
    private RoadRepository $roadRepository,
    private RoadPartRepository $roadPartRepository,
    private PostcodesRepository $postcodesRepository,
    private StaggingRepository $staggingRepository,
    private LocationArrayTransformerService $locationArrayTransformerService,
    private Security $security,
    private EntityManagerInterface $entityManager,
  ) {}

  #[Route('/warehouse/picking', name: 'app_picking')]
  public function index(): Response
  {
    return $this->render('picking/index.html.twig', [
      'controller_name' => 'PickingController',
    ]);
  }

  #[Route('/getLocationsLight', name: 'get_locations_light', methods: ['GET'])]
  public function getLocationsLight(): Response
  {
    return $this->json($this->locationArrayTransformerService->transformAllInPairLight());
  }

  #[Route('/deleteAllRoads', name: 'delete_all_roads', methods: ['GET'])]
  public function deleteAllRoads(): Response
  {
    $allRoads = $this->roadRepository->findAll();

    foreach ($allRoads as $road) {
      foreach ($road->getRoadParts() as $roadPart) {
        $road->removeRoadPart($roadPart);
      }
      $this->entityManager->flush();
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

  private function createRoadPart($road, $partNumber): RoadPart
  {
    $roadPart = new RoadPart();
    $road->addRoadPart($roadPart);
    $roadPart->setNumber($partNumber);
    $roadPart->setStagged(false);
    $this->entityManager->persist($roadPart);

    return $roadPart;
  }

  private function getRoadPart($road): RoadPart
  {
    $roadPart = $this->roadPartRepository->findOneBy(
      ['road' => $road],
      ['number' => 'DESC']
    );

    if (!$roadPart) {
      $roadPart = $this->createRoadPart($road, 1);
    }

    if (count($roadPart->getBags()) >= 6) {
      $incrementedNumber = $roadPart->getNumber() + 1;
      $roadPart = $this->createRoadPart($road, $incrementedNumber);
    }

    return $roadPart;
  }

  private function getOrCreateRoad($bag): Road
  {
    $postcode = $this->bagRepository->findBagPostcode($bag);

    $postcodeEntity = $this->postcodesRepository->findOneBy(['name' => $postcode]);
    $groupName = $postcodeEntity?->getGroupPostcodes()?->getName();

    $road = $this->roadRepository->findOneBy(['name' => $groupName]);

    if (!$road) {
      $road = new Road();
      $road->setName($groupName);
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

  #[Route('/getStaggingAreas', name: 'get_stagging_areas', methods: ['GET'])]
  public function getStaggingAreas(): Response
  {
    return $this->json($this->staggingRepository->getAllOrderedStagging());
  }

  #[Route('/getCurrentUserRoadpart', name: 'get_current_user_roadpart', methods: ['GET'])]
  public function getCurrentUserRoadpart(): Response
  {
    $user = $this->security->getUser();
    $roadPart = $this->roadPartRepository->findOnHasUserNotStagged($user);

    if (!$roadPart) {
      return $this->json(['error' => 'No road part available'], 404);
    }

    return $this->json($this->roadPartRepository->toArray($roadPart));
  }

  #[Route('/setRoadToUser', name: 'set_road_to_user', methods: ['POST'])]
  public function setRoadToUser(): Response
  {
    $user = $this->security->getUser();

    if (!$user) {
      return $this->json(['error' => 'Unauthorized'], 401);
    }

    $currentRoadPart = $this->roadPartRepository->findOnHasUserNotStagged($user);

    if ($currentRoadPart) {
      throw $this->createNotFoundException(
        'Road part is already set'
      );
    }

    $roadPart = $this->roadPartRepository->findFirstWithNoUser();

    if (!$roadPart) {
      return $this->json(['error' => 'No road part available'], 404);
    }

    $roadPart->setUser($user);
    $this->entityManager->flush();

    return $this->json($this->roadPartRepository->toArray($roadPart));
  }
}
