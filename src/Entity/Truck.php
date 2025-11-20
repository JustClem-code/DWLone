<?php

namespace App\Entity;

use App\Repository\TruckRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TruckRepository::class)]
class Truck
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\Column(name: "name", length: 7)]
  private ?string $name = null;

  #[ORM\Column]
  private ?\DateTime $ExpectedDate = null;

  #[ORM\Column(nullable: true)]
  private ?\DateTime $DeliveryDate = null;

  /**
   * @var Collection<int, Pallet>
   */
  #[ORM\OneToMany(targetEntity: Pallet::class, mappedBy: 'truck')]
  private Collection $pallets;

  #[ORM\OneToOne(mappedBy: 'truck', cascade: ['persist', 'remove'])]
  private ?Dock $dock = null;

  #[ORM\Column(nullable: true)]
  private ?\DateTime $DepartureDate = null;

  #[ORM\ManyToOne(inversedBy: 'trucks')]
  private ?user $userDelDate = null;

  #[ORM\ManyToOne(inversedBy: 'departureTrucks')]
  private ?user $userDepDate = null;

  public function __construct()
  {
    $this->pallets = new ArrayCollection();
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getName(): ?string
  {
    return $this->name;
  }

  public function setName(string $name): static
  {
    $this->name = $name;

    return $this;
  }

  public function getExpectedDate(): ?\DateTime
  {
    return $this->ExpectedDate;
  }

  public function setExpectedDate(\DateTime $ExpectedDate): static
  {
    $this->ExpectedDate = $ExpectedDate;

    return $this;
  }

  public function getDeliveryDate(): ?\DateTime
  {
    return $this->DeliveryDate;
  }

  public function setDeliveryDate(?\DateTime $DeliveryDate): static
  {
    $this->DeliveryDate = $DeliveryDate;

    return $this;
  }

  /**
   * @return Collection<int, Pallet>
   */
  public function getPallets(): Collection
  {
    return $this->pallets;
  }

  public function addPallet(Pallet $pallet): static
  {
    if (!$this->pallets->contains($pallet)) {
      $this->pallets->add($pallet);
      $pallet->setTruck($this);
    }

    return $this;
  }

  public function removePallet(Pallet $pallet): static
  {
    if ($this->pallets->removeElement($pallet)) {
      // set the owning side to null (unless already changed)
      if ($pallet->getTruck() === $this) {
        $pallet->setTruck(null);
      }
    }

    return $this;
  }

  public function getDock(): ?Dock
  {
    return $this->dock;
  }

  public function setDock(?Dock $dock): static
  {
    // unset the owning side of the relation if necessary
    if (($dock === null || $dock->getTruck() !== $this) && $this->dock !== null) {
      $this->dock->setTruck(null);
    }

    // set the owning side of the relation if necessary
    if ($dock !== null && $dock->getTruck() !== $this) {
      $dock->setTruck($this);
    }

    $this->dock = $dock;

    return $this;
  }

  public function getDepartureDate(): ?\DateTime
  {
      return $this->DepartureDate;
  }

  public function setDepartureDate(?\DateTime $DepartureDate): static
  {
      $this->DepartureDate = $DepartureDate;

      return $this;
  }

  public function getUserDelDate(): ?user
  {
      return $this->userDelDate;
  }

  public function setUserDelDate(?user $userDelDate): static
  {
      $this->userDelDate = $userDelDate;

      return $this;
  }

  public function getUserDepDate(): ?user
  {
      return $this->userDepDate;
  }

  public function setUserDepDate(?user $userDepDate): static
  {
      $this->userDepDate = $userDepDate;

      return $this;
  }
}
