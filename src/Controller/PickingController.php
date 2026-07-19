<?php

namespace App\Controller;

use App\Repository\Trait\RepositoryTrait;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

use App\Repository\BagRepository;
use App\Repository\RoadPartRepository;
use App\Repository\StaggingRepository;
use App\Repository\CartRepository;
use Symfony\Bundle\SecurityBundle\Security;

use App\Service\LocationArrayTransformerService;

use App\Entity\Bag;
use App\Entity\RoadPart;
use App\Entity\Stagging;
use App\Entity\Cart;

final class PickingController extends AbstractController
{
  public function __construct(
    private BagRepository $bagRepository,
    private RoadPartRepository $roadPartRepository,
    private StaggingRepository $staggingRepository,
    private CartRepository $cartRepository,
    private LocationArrayTransformerService $locationArrayTransformerService,
    private Security $security,
    private EntityManagerInterface $entityManager,
  ) {}

  use RepositoryTrait;

  #[Route('/warehouse/picking', name: 'app_picking')]
  public function index(): Response
  {
    return $this->render('picking/index.html.twig', [
      'controller_name' => 'PickingController',
    ]);
  }

  #[Route('/getLocationsLight', name: 'get_locations_light', methods: ['GET'])]
  public function getLocationsLight(): Response
  {
    return $this->json($this->locationArrayTransformerService->transformAllInPairLight());
  }

  #[Route('/getStaggingAreas', name: 'get_stagging_areas', methods: ['GET'])]
  public function getStaggingAreas(): Response
  {
    return $this->json($this->staggingRepository->getAllOrderedStagging());
  }

  private function currentUserRoadpart(): ?Roadpart
  {
    $user = $this->security->getUser();

    return $this->roadPartRepository->findOnHasUserNotStagged($user);
  }

  #[Route('/getHasUnpickedRoadParts', name: 'get_has_unpicked_road_parts', methods: ['GET'])]
  public function getHasUnpickedRoadParts(): Response
  {
    return $this->json($this->roadPartRepository->hasUnpickedRoadParts());
  }

  #[Route('/getCurrentUserRoadpart', name: 'get_current_user_roadpart', methods: ['GET'])]
  public function getCurrentUserRoadpart(): Response
  {
    $roadPart = $this->currentUserRoadpart();

    if (!$roadPart) {
      return $this->json([null], 204);
    }

    return $this->json($this->roadPartRepository->toArray($roadPart));
  }

  #[Route('/setRoadToUser', name: 'set_road_to_user', methods: ['POST'])]
  public function setRoadToUser(): Response
  {
    $user = $this->security->getUser();

    if (!$user) {
      return $this->json(['error' => 'Unauthorized'], 401);
    }

    $currentRoadPart = $this->roadPartRepository->findOnHasUserNotStagged($user);

    if ($currentRoadPart) {
      return $this->json(['error' => 'Road part is already set'], 404);
    }

    $roadPart = $this->roadPartRepository->findFirstWithNoUser();

    if (!$roadPart) {
      return $this->json(['error' => 'No road part available'], 404);
    }

    $roadPart->setUser($user);
    $this->entityManager->flush();

    return $this->json($this->roadPartRepository->toArray($roadPart));
  }

  #[Route('/setCartToRoadPart/{id}', name: 'set_cart_to_roadPart')]
  public function setCartToRoadPart(
    Request $request,
    int $id,
  ): Response {
    $formData = $request->getPayload()->get('staggingId');

    $roadPart = $this->entityManager->getRepository(RoadPart::class)->find($id);

    $stagging = $this->findOrNull($this->entityManager->getRepository(Stagging::class), $formData);

    if (!$roadPart) {
      return $this->json(['error' => 'No road part available'], 404);
    }

    if (!$stagging) {
      return $this->json(['error' => 'No stagging area available'], 404);
    }

    if ($roadPart->getRoad()->getStagging() !== $stagging) {
      return $this->json(['error' => 'Go to the staggin area'], 404);
    }

    $cart = $this->cartRepository->findOneWithoutRoadPart($stagging);

    if (!$cart) {
      return $this->json(['error' => 'No cart available'], 404);
    }

    $roadPart->startPicking($cart);

    $this->entityManager->flush();

    return $this->json($this->roadPartRepository->toArray($roadPart));
  }

  private function allBagsPicked(iterable $bags): bool
  {
    return empty($bags) || array_all(iterator_to_array($bags), fn($bag) => $bag->isPicked());
  }

  #[Route('/staggingCart/{id}', name: 'stagging_cart')]
  public function staggingCart(
    Request $request,
    int $id,
  ): Response {
    $formData = $request->getPayload()->get('staggingId');
    $roadPart = $this->entityManager->getRepository(RoadPart::class)->find($id);
    $stagging = $this->findOrNull($this->entityManager->getRepository(Stagging::class), $formData);

    if (!$roadPart) {
      return $this->json(['error' => 'No road part available'], 404);
    }

    if ($roadPart->isStagged()) {
      return $this->json(['error' => 'Road part is already stagged'], 404);
    }

    if (!$stagging) {
      return $this->json(['error' => 'No stagging area available'], 404);
    }

    if ($roadPart->getRoad()->getStagging() !== $stagging) {
      return $this->json(['error' => 'Go to the staggin area'], 404);
    }

    if (!$this->allBagsPicked($roadPart->getBags())) {
      return $this->json(['error' => 'Not all the bags are picked!'], 404);
    }

    $roadPart->finishPicking();
    $this->entityManager->flush();

    return $this->json(
      [
        'hasUnpickedRoadParts' => $this->roadPartRepository->hasUnpickedRoadParts(),
        'roadPart' => $this->roadPartRepository->toArray($roadPart)
      ]
    );
  }

  #[Route('/pickingBag/{id}', name: 'pick_bag', methods: ['POST'])]
  public function pickBag(
    Request $request,
    int $id,
  ): Response {
    $formData = $request->getPayload()->get('bagId');
    $cart = $this->entityManager->getRepository(Cart::class)->find($id);
    $bag = $this->findOrNull($this->entityManager->getRepository(Bag::class), $formData);

    $roadPart = $this->currentUserRoadpart();
    $bagToPick = $this->bagRepository->findUnpickedByRoadPart($roadPart)[0];

    if ($bag !== $bagToPick) {
      return $this->json(['error' => 'Wrong bag'], 404);
    }

    if ($cart !== $roadPart->getCart()) {
      return $this->json(['error' => 'Wrong cart'], 404);
    }

    $bag->setPicked(true);

    $this->entityManager->flush();

    return $this->json($this->roadPartRepository->toArray($roadPart));
  }
}
