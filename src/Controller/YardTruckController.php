<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

use App\Repository\DockRepository;
use App\Repository\TruckRepository;

use App\Entity\Dock;
use App\Entity\Truck;

final class YardTruckController extends AbstractController
{
  #[Route('/yard/truck', name: 'app_yard_truck')]
  public function index(): Response
  {
    return $this->render('yard_truck/index.html.twig', [
      'controller_name' => 'YardTruckController',
    ]);
  }

  #[Route('/getdocks', name: 'get_docks_list', methods: ['GET'])]
  public function getDocks(DockRepository $repository): Response
  {
    $datas = $repository->transformAll();
    return $this->json($datas);
  }

  #[Route('/gettrucks', name: 'get_trucks_list', methods: ['GET'])]
  public function getTrucks(TruckRepository $repository): Response
  {
    $datas = $repository->transformAll();
    return $this->json($datas);
  }

  private function findOrNull(object $repository, ?int $id): ?object {
    if ($id === null) {
        return null;
    }
    return $repository->find($id);
}


  #[Route('/dockingTruck/{id}', name: 'docking_truck')]
  public function dockingTruck(
    Request $request,
    EntityManagerInterface $entityManager,
    int $id,
  ): Response {
    $formData = $request->getPayload()->get('id');

    $truck = $entityManager->getRepository(Truck::class)->find($id);
    $dock = $this->findOrNull($entityManager->getRepository(Dock::class), $formData);

    if (!$truck) {
      throw $this->createNotFoundException(
        'No truck found for id ' . $id
      );
    }

    $truck->setDock($dock);

    $entityManager->flush();

    return new Response('Dock:' . $dock?->getName() . ' ' . 'Truck:' . $truck->getWrid());
  }
}
