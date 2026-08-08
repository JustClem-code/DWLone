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

  private function truckResponse(
    Truck $truck,
    ?Dock $dock = null,
    ?Dock $previousDock = null,
  ) {
    return $this->json([
      'truck' => $this->truckRepository->toArray($truck),
      'dock' => $dock ? $this->dockRepository->toArray($dock) : null,
      'previousDock' => $previousDock ? $this->dockRepository->toArray($previousDock) : null,
    ]);
  }

  #[Route('/resetDockingTruck/{id}', name: 'reset_docking_truck')]
  public function resetDockingTruck(int $id): Response
  {

    $truck = $this->truckRepository->find($id);

    if (!$truck) {
      return $this->json(['error' => 'No truck found for id ' . $id], 404);
    }

    $truck->resetTruck();

    $this->entityManager->flush();

    return $this->truckResponse($truck);
  }

  #[Route('/dockingTruck/{id}', name: 'docking_truck')]
  public function dockingTruck(
    Request $request,
    int $id,
  ): Response {
    $formData = $request->getPayload()->get('id');

    $truck = $this->truckRepository->find($id);
    $dock = $this->findOrNull($this->dockRepository, $formData);

    if (!$truck) {
      return $this->json(['error' => 'No truck found for id ' . $id], 404);
    }

    if ($dock?->getTruck()) {
      return $this->json(['error' => 'Dock is not available'], 404);
    }

    $previousDock = $truck->getDock() ? $truck->getDock() : null;
    $date = new \DateTime();

    $truck->setDock($dock);
    $truck->setDeliveryDate($date);
    $truck->setUserDelDate($this->security->getUser());

    $this->entityManager->flush();

    return $this->truckResponse(
      truck: $truck,
      dock: $dock,
      previousDock: $previousDock,
    );
  }

  #[Route('/unDockingTruck/{id}', name: 'undocking_truck')]
  public function unDockingTruck(int $id): Response
  {

    $truck = $this->truckRepository->find($id);

    if (!$truck) {
      return $this->json(['error' => 'No truck found for id ' . $id], 404);
    }

    if (!$truck->getDock()) {
      return $this->json(['error' => 'Truck is not docked'], 404);
    }

    $previousDock = $truck->getDock();
    $date = new \DateTime();

    $truck->setDock(null);
    $truck->setDepartureDate($date);
    $truck->setUserDepDate($this->security->getUser());

    $this->entityManager->flush();

    return $this->truckResponse(
      previousDock: $previousDock,
      truck: $truck,
    );
  }
}
