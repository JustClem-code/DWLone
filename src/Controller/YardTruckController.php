<?php

namespace App\Controller;

use App\Repository\Trait\RepositoryTrait;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

use App\Repository\DockRepository;
use App\Repository\TruckRepository;

use App\Entity\Dock;
use App\Entity\Truck;

use Symfony\Bundle\SecurityBundle\Security;

final class YardTruckController extends AbstractController
{

  public function __construct(
    private Security $security,
    private EntityManagerInterface $entityManager,
    private DockRepository $dockRepository,
    private TruckRepository $truckRepository,
  ) {}

  use RepositoryTrait;

  #[Route('/yard/truck', name: 'app_yard_truck')]
  public function index(): Response
  {
    return $this->render('yard_truck/index.html.twig', [
      'controller_name' => 'YardTruckController',
    ]);
  }

  #[Route('/getdocks', name: 'get_docks_list', methods: ['GET'])]
  public function getDocks(): Response
  {
    return $this->json($this->dockRepository->transformAll());
  }

  #[Route('/gettrucks', name: 'get_trucks_list', methods: ['GET'])]
  public function getTrucks(): Response
  {
    return $this->json($this->truckRepository->transformAll());
  }

  #[Route('/dockingTruck/{id}', name: 'docking_truck')]
  public function dockingTruck(
    Request $request,
    int $id,
  ): Response {
    $formData = $request->getPayload()->get('id');
    $reset = $request->getPayload()->get('reset');

    $truck = $this->entityManager->getRepository(Truck::class)->find($id);
    $dock = $this->findOrNull($this->entityManager->getRepository(Dock::class), $formData);
    $previousDock = null;

    if (!$truck) {
      return $this->json(['error' => 'No truck found for id ' . $id], 404);
    }

    if ($dock?->getTruck()) {
      return $this->json(['error' => 'Dock is not available'], 404);
    }

    if ($truck->getDock()) {
      $previousDock = $truck->getDock();
    }

    $truck->setDock($dock);

    if ($reset) {
      $truck->resetTruck();
    } else {
      $date = new \DateTime();
      if ($truck->getDock()) {
        // Docking truck
        $truck->setDeliveryDate($date);
        $truck->setUserDelDate($this->security->getUser());
      } else {
        // Undocking truck
        $truck->setDepartureDate($date);
        $truck->setUserDepDate($this->security->getUser());
      }
    }

    $this->entityManager->flush();

    return $this->json(
      [
        'dock' => $dock ? $this->dockRepository->toArray($dock) : null,
        'previousDock' => $previousDock ? $this->dockRepository->toArray($previousDock) : null,
        'truck' => $this->truckRepository->toArray($truck)
      ]
    );
  }
}
