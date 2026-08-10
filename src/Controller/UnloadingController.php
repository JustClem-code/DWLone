<?php

namespace App\Controller;

use App\Repository\Trait\RepositoryTrait;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

use App\Repository\DockRepository;
use App\Repository\PalletRepository;

final class UnloadingController extends AbstractController
{
  public function __construct(
    private Security $security,
    private EntityManagerInterface $entityManager,
    private DockRepository $dockRepository,
    private PalletRepository $palletRepository
  ) {}

  use RepositoryTrait;

  #[Route('/warehouse/unloading', name: 'app_unloading')]
  public function index(): Response
  {
    return $this->render('unloading/index.html.twig', [
      'controller_name' => 'UnloadingController',
    ]);
  }

  #[Route('/getoccupieddocks', name: 'get_occupied_docks_list', methods: ['GET'])]
  public function getDocks(): Response
  {
    return $this->json($this->dockRepository->transformOccupiedDocks());
  }

  #[Route('/getpalletsonfloor', name: 'get_pallets_on_floor_list', methods: ['GET'])]
  public function getPalletsOnFloor(): Response
  {
    return $this->json($this->palletRepository->findAllHasUser());
  }

  #[Route('/resetUnloadingPallet/{id}', name: 'reset_unloading_pallet')]
  public function resetUnloadingPallet(int $id): Response
  {
    $pallet = $this->palletRepository->find($id);

    if (!$pallet) {
      return $this->json(['error' => 'No pallet found for id ' . $id], 404);
    }

    $pallet->setUserId(null);

    $this->entityManager->flush();

    return $this->json(
      $this->palletRepository->toArray($pallet)
    );
  }

  #[Route('/unloadingPallet/{id}', name: 'unloading_pallet')]
  public function unloadingPallet(int $id): Response {
    $pallet = $this->palletRepository->find($id);

    if (!$pallet) {
      return $this->json(['error' => 'No pallet found for id ' . $id], 404);
    }

    $pallet->setUserId($this->security->getUser());

    $this->entityManager->flush();

    return $this->json(
      $this->palletRepository->toArray($pallet)
    );
  }
}
