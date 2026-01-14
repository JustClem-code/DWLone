<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use App\Repository\BagRepository;
use App\Service\BagArrayTransformer;

final class DashboardController extends AbstractController
{
  public function __construct(private BagArrayTransformer $bagArrayTransformer) {}

  #[Route('/', name: 'app_dashboard')]
  public function index(): Response
  {
    return $this->render('dashboard/index.html.twig', [
      'controller_name' => 'DashboardController',
    ]);
  }

  #[Route('/getBags', name: 'get_bags_list', methods: ['GET'])]
  public function getBags(BagRepository $repository): Response
  {
    $bags = $repository->findAllHasLocation();
    return $this->json($this->bagArrayTransformer->transformAll($bags));
  }
}
