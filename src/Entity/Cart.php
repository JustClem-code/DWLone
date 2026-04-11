<?php

namespace App\Entity;

use App\Repository\CartRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CartRepository::class)]
class Cart
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\ManyToOne(inversedBy: 'carts')]
  private ?Stagging $stagging = null;

  #[ORM\OneToOne(inversedBy: 'cart', cascade: ['persist', 'remove'])]
  private ?RoadPart $roadPartId = null;

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getStagging(): ?Stagging
  {
    return $this->stagging;
  }

  public function setStagging(?Stagging $stagging): static
  {
    $this->stagging = $stagging;

    return $this;
  }

  public function getRoadPartId(): ?RoadPart
  {
      return $this->roadPartId;
  }

  public function setRoadPartId(?RoadPart $roadPartId): static
  {
      $this->roadPartId = $roadPartId;

      return $this;
  }
}
