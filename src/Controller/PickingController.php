<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PickingController extends AbstractController
{
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
    $group_01 = [
      "44170",
      "44140",
      "44150",
      "44150",
      "44320",
      "44410",
    ];
    $group_02 = [
      "44460",
      "44450",
      "44115",
      "44740",
      "44160",
      "44130",
    ];
    $group_03 = [
      "44540",
      "44830",
      "44340",
      "44580",
      "44190",
      "44130",
    ];
    $group_04 = [
      "44830",
      "44750",
      "44470",
      "44390",
      "44690",
      "44110",
    ];
    $group_05 = [
      "44640",
      "44190",
      "44360",
      "44220",
      "44480",
      "44320",
    ];
    $group_06 = [
      "44530",
      "44350",
      "44115",
      "44610",
      "44500",
      "44240",
    ];
    $group_07 = [
      "44118",
      "44620",
      "44140",
      "44420",
      "44490",
      "44430",
    ];
    $group_08 = [
      "44330",
      "44640",
      "44540",
      "44510",
      "44760",
      "44850",
    ];
    $group_09 = [
      "44370",
      "44110",
      "44590",
      "44270",
      "44260",
      "44470",
      "44420",
    ];
    $group_10 = [
      "44780",
      "44690",
      "44550",
      "44310",
      "44330",
      "44000",
      "44800",
    ];
  }

  // function qui trouves les sacs d'un postcode

  // Function qui créer une route avec tous les sacs des postcodes

  // Function qui génère et renvoie toutes les routes
}
