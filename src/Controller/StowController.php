<?php

namespace App\Controller;

use App\Entity\Package;
use App\Repository\LocationRepository;
use App\Repository\PackageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use App\Service\LocationArrayTransformer;

use Symfony\Bundle\SecurityBundle\Security;

final class StowController extends AbstractController
{
  public function __construct(private Security $security, private LocationArrayTransformer $locationArrayTransformer) {}

  #[Route('/warehouse/stow', name: 'app_stow')]
  public function index(): Response
  {
    return $this->render('stow/index.html.twig', [
      'controller_name' => 'StowController',
    ]);
  }

  #[Route('/getlocations', name: 'get_locations_list', methods: ['GET'])]
  public function getLocations(LocationRepository $repository): Response
  {

    $locations = $repository->findAll();
    return $this->json($this->locationArrayTransformer->transformAll($locations));
  }

  #[Route('/setUserStow/{id}', name: 'set_user_stow', methods: ['POST'])]
  public function setPackageUserStow(
    Package $package,
    EntityManagerInterface $entityManager,
    PackageRepository $packageRepository,
    int $id,
  ): Response {
    $package = $entityManager->getRepository(Package::class)->find($id);

    if (!$package) {
      throw $this->createNotFoundException(
        'No package found for id ' . $id
      );
    }

    $package->setUserStow($this->security->getUser());

    $entityManager->flush();

    return $this->json($packageRepository->toArray($package));
  }
}
