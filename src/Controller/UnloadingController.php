<?php

namespace App\Controller;

use App\Controller\Trait\RepositoryTrait;
use App\Entity\Dock;
use App\Entity\Pallet;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

use App\Repository\DockRepository;
use App\Repository\PalletRepository;
use Symfony\Bundle\SecurityBundle\Security;

final class UnloadingController extends AbstractController
{
  public function __construct(private Security $security) {}

  use RepositoryTrait;

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

  #[Route('/getpalletsonfloor', name: 'get_pallets_on_floor_list', methods: ['GET'])]
  public function getPalletsOnFloor(PalletRepository $repository): Response
  {
    $datas = $repository->findAllHasUser();
    return $this->json($datas);
  }

  #[Route('/unloadingPallet/{id}', name: 'unloading_pallet')]
  public function unloadingPallet(
    Request $request,
    EntityManagerInterface $entityManager,
    int $id,
  ): Response {
    $formData = $request->getPayload()->get('id');
    $reset = $request->getPayload()->get('reset');

    $pallet = $entityManager->getRepository(Pallet::class)->find($id);
    $dock = $this->findOrNull($entityManager->getRepository(Dock::class), $formData);

    if (!$pallet) {
      throw $this->createNotFoundException(
        'No pallet found for id ' . $id
      );
    }

    if ($reset) {
      $pallet->setUserId(null);
    } else {
      $pallet->setUserId($this->security->getUser());
    }

    $entityManager->flush();

    return $this->json(
      [
        'dockId' => $dock?->getId() ?? null,
        'palletId' => $pallet->getId(),
        'userId' => $pallet->getUserId()?->getId() ?? null,
        'userName' => $pallet->getUserId()?->getUsername() ?? null,
      ]
    );
  }
}
