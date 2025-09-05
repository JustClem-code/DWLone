<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use App\Repository\TruckRepository;

final class YardTruckController extends AbstractController
{
  #[Route('/yard/truck', name: 'app_yard_truck')]
  public function index(): Response
  {
    return $this->render('yard_truck/index.html.twig', [
      'controller_name' => 'YardTruckController',
    ]);
  }

  #[Route('/gettrucks', name: 'get_trucks_list', methods: ['GET'])]
  public function getTrucks(TruckRepository $repository): Response
  {
    $datas = $repository->transformAll();
    return $this->json($datas);
  }
}
