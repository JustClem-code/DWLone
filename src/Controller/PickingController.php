<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

use App\Repository\BagRepository;
use App\Repository\RoadRepository;
use App\Repository\PostcodesRepository;

use App\Service\LocationArrayTransformerService;

use App\Entity\Road;

final class PickingController extends AbstractController
{
  public function __construct(
    private BagRepository $bagRepository,
    private RoadRepository $roadRepository,
    private PostcodesRepository $postcodesRepository,
    private LocationArrayTransformerService $locationArrayTransformerService,
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

  private function getAllBagsWithPackages(): array
  {
    return $this->bagRepository->findAllHasLocationAndPackages() ?? [];
  }

  #[Route('/getAllRoads', name: 'get_all_roads', methods: ['GET'])]
  public function getAllRoads(): Response
  {
    $allRoads = $this->roadRepository->findAll();

    return $this->json($this->roadRepository->transformAll($allRoads));
  }

  #[Route('/deleteAllRoads', name: 'delete_all_roads', methods: ['GET'])]
  public function deleteAllRoads(EntityManagerInterface $entityManager): Response
  {
    $allRoads = $this->roadRepository->findAll();

    foreach ($allRoads as $road) {
      $this->roadRepository->setBagsToNull($road);
      $entityManager->remove($road);
    }

    $entityManager->flush();

    return $this->getAllRoads();
  }

  #[Route('/generateAllRoads', name: 'generate_all_roads', methods: ['GET'])]
  public function generateAllRoads(EntityManagerInterface $entityManager): Response
  {
    $bags = $this->getAllBagsWithPackages();

    foreach ($bags as $bag) {

      $postcode = $this->bagRepository->findBagPostcode($bag);

      $groupName = $this->postcodesRepository->findOneBy(['name' => $postcode])->getGroupPostcodes()?->getName();

      if ($groupName === null) {
        // gérer le cas "pas de groupe"
      }

      if (!$this->roadRepository->findOneBy(['name' => $groupName])) {
        $road = new Road();
        $road->setName($groupName);
        $entityManager->persist($road);
      } else {
        $road = $this->roadRepository->findOneBy(['name' => $groupName]);
      }

      $bag->setRoad($road);
      $entityManager->flush();
    }

    return $this->getAllRoads();
  }
}
