<?php

namespace App\Controller;

use App\Repository\BagRepository;
use App\Repository\RoadRepository;
use App\Entity\Road;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

final class PickingController extends AbstractController
{
  public function __construct(
    private BagRepository $bagRepository,
    private RoadRepository $roadRepository,
  ) {}

  #[Route('/warehouse/picking', name: 'app_picking')]
  public function index(): Response
  {
    return $this->render('picking/index.html.twig', [
      'controller_name' => 'PickingController',
    ]);
  }

  // Function qui groupe les postcodes par 6 ou 7
  // TODO: Créer une table pour pouvoir changer les groupes dans le dashboard
  private function groupPostcodes()
  {
    return [
      'CA_A_01' => [
        "44170",
        "44140",
        "44150",
        "44150",
        "44320",
        "44410",
      ],
      'CA_A_02' => [
        "44460",
        "44450",
        "44115",
        "44740",
        "44160",
        "44130",
      ],
      'CA_A_03' => [
        "44540",
        "44830",
        "44340",
        "44580",
        "44190",
        "44130",
      ],
      'CA_A_04' => [
        "44830",
        "44750",
        "44470",
        "44390",
        "44690",
        "44110",
      ],
      'CA_A_05' => [
        "44640",
        "44190",
        "44360",
        "44220",
        "44480",
        "44320",
      ],
      'CA_A_06' => [
        "44530",
        "44350",
        "44115",
        "44610",
        "44500",
        "44240",
      ],
      'CA_A_07' => [
        "44118",
        "44620",
        "44140",
        "44420",
        "44490",
        "44430",
      ],
      'CA_A_08' => [
        "44330",
        "44640",
        "44540",
        "44510",
        "44760",
        "44850",
      ],
      'CA_A_09' => [
        "44370",
        "44110",
        "44590",
        "44270",
        "44260",
        "44470",
        "44420",
      ],
      'CA_A_10' => [
        "44780",
        "44690",
        "44550",
        "44310",
        "44330",
        "44000",
        "44800",
      ]
    ];
  }

  private function findPostcodeGroup(string $postcode): ?string
  {
    foreach ($this->groupPostcodes() as $group => $postcodes) {
      if (in_array($postcode, $postcodes, true)) {
        return $group;
      }
    }

    return null;
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

  #[Route('/generateAllRoads', name: 'generate_all_roads', methods: ['GET'])]
  public function generateAllRoads(EntityManagerInterface $entityManager): Response
  {
    $bags = $this->getAllBagsWithPackages();

    foreach ($bags as $bag) {

      $postcode = $this->bagRepository->findBagPostcode($bag);

      $groupName = $this->findPostcodeGroup($postcode);

      if ($groupName === null) {
        // gérer le cas "pas de groupe"
      }

      if (!$this->roadRepository->findOneByName($groupName)) {
        $road = new Road();
        $road->setName($groupName);
        $entityManager->persist($road);
      } else {
        $road = $this->roadRepository->findOneByName($groupName);
      }

      $bag->setRoad($road);
      $entityManager->flush();
    }

    return $this->getAllRoads();
  }
}
