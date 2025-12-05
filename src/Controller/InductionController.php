<?php

namespace App\Controller;

use App\Controller\Trait\RepositoryTrait;
use App\Entity\Package;
use App\Entity\Pallet;
use App\Repository\PalletRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

final class InductionController extends AbstractController
{
  use RepositoryTrait;

  #[Route('/warehouse/induction', name: 'app_induction')]
  public function index(): Response
  {
    return $this->render('induction/index.html.twig', [
      'controller_name' => 'InductionController',
    ]);
  }

  #[Route('/setLocation/{id}', name: 'unloading_pallet')]
  public function unloadingPallet(
    Request $request,
    EntityManagerInterface $entityManager,
    int $id,
    PalletRepository $repository,
  ): Response {
    $package = $entityManager->getRepository(Package::class)->find($id);

    if (!$package) {
      throw $this->createNotFoundException(
        'No pallet found for id ' . $id
      );
    }

    $package->setLocation();


    $entityManager->flush();

    return $this->json(
      $repository->toArray($pallet)
    );
  }
}
