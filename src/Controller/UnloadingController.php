<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use App\Repository\DockRepository;

final class UnloadingController extends AbstractController
{
  #[Route('/warehouse/unloading', name: 'app_unloading')]
  public function index(): Response
  {
    return $this->render('unloading/index.html.twig', [
      'controller_name' => 'UnloadingController',
    ]);
  }

  #[Route('/getoccupieddocks', name: 'get_occupied_docks_list', methods: ['GET'])]
  public function getDocks(DockRepository $repository): Response
  {
    $datas = $repository->transformOccupiedDocks();
    return $this->json($datas);
  }
}
