<?php

namespace App\Controller;

use App\Repository\LocationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use App\Service\LocationArrayTransformer;

final class StowController extends AbstractController
{
public function __construct(private LocationArrayTransformer $locationArrayTransformer) {}

  #[Route('/warehouse/stow', name: 'app_stow')]
  public function index(): Response
  {
    return $this->render('stow/index.html.twig', [
      'controller_name' => 'StowController',
    ]);
  }

  #[Route('/getlocations', name: 'get_locations_list', methods: ['GET'])]
  public function getDocks(LocationRepository $repository): Response
  {

    $locations = $repository->findAll();
    return $this->json($this->locationArrayTransformer->transformAll($locations));
  }
}
