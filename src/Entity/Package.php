<?php

namespace App\Entity;

use App\Repository\PackageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PackageRepository::class)]
class Package
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $weight = null;

    #[ORM\ManyToOne(inversedBy: 'packages')]
    private ?Packaging $packaging = null;

    #[ORM\ManyToOne(inversedBy: 'packages')]
    private ?Associate $Associate = null;

    #[ORM\ManyToOne(inversedBy: 'packages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Order $OrderId = null;

    #[ORM\ManyToOne(inversedBy: 'packages')]
    private ?Location $location = null;

    #[ORM\ManyToOne(inversedBy: 'packages')]
    private ?Bag $bag = null;

    #[ORM\ManyToOne(inversedBy: 'packages')]
    private ?Pallet $Pallet = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(int $weight): static
    {
        $this->weight = $weight;

        return $this;
    }

    public function getPackaging(): ?Packaging
    {
        return $this->packaging;
    }

    public function setPackaging(?Packaging $packaging): static
    {
        $this->packaging = $packaging;

        return $this;
    }

    public function getAssociate(): ?Associate
    {
        return $this->Associate;
    }

    public function setAssociate(?Associate $Associate): static
    {
        $this->Associate = $Associate;

        return $this;
    }

    public function getOrderId(): ?Order
    {
        return $this->OrderId;
    }

    public function setOrderId(?Order $OrderId): static
    {
        $this->OrderId = $OrderId;

        return $this;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getBag(): ?Bag
    {
        return $this->bag;
    }

    public function setBag(?Bag $bag): static
    {
        $this->bag = $bag;

        return $this;
    }

    public function getPallet(): ?Pallet
    {
        return $this->Pallet;
    }

    public function setPallet(?Pallet $Pallet): static
    {
        $this->Pallet = $Pallet;

        return $this;
    }
}
