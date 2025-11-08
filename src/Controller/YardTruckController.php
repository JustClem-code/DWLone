<?php

namespace App\Controller;

use App\Controller\Trait\RepositoryTrait;

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
  use RepositoryTrait;

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

  #[Route('/dockingTruck/{id}', name: 'docking_truck')]
  public function dockingTruck(
    Request $request,
    EntityManagerInterface $entityManager,
    int $id,
  ): Response {
    $formData = $request->getPayload()->get('id');
    $reset = $request->getPayload()->get('reset');

    $truck = $entityManager->getRepository(Truck::class)->find($id);
    $dock = $this->findOrNull($entityManager->getRepository(Dock::class), $formData);
    $previousDock = null;

    if (!$truck) {
      throw $this->createNotFoundException(
        'No truck found for id ' . $id
      );
    }

    if ($dock?->getTruck()) {
      return $this->json(['status' => 'error', 'message' => 'Dock is not available'], 400);
    }

    if ($truck->getDock()) {
      $previousDock = $truck->getDock();
    }

    $truck->setDock($dock);

    if ($reset) {
      $truck->setDeliveryDate(null);
      $truck->setDepartureDate(null);
    } else {
      $date = new \DateTime();
      if ($truck->getDock()) {
        $truck->setDeliveryDate($date);
      } else {
        $truck->setDepartureDate($date);
      }
    }


    $entityManager->flush();

    return $this->json(
      [
        'dockId' => $dock?->getId() ?? null,
        'dockName' => $dock?->getName() ?? null,
        'previousDockId' => $previousDock?->getId() ?? null,
        'previousDockName' => $previousDock?->getName() ?? null,
        'truckId' => $truck->getId(),
        'truckName' => $truck->getName(),
        'deliveryDate' => $truck->getDeliveryDate(),
        'departureDate' => $truck->getDepartureDate()
      ]
    );
  }
}
