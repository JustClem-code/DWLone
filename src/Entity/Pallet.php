<?php

namespace App\Entity;

use App\Repository\PalletRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PalletRepository::class)]
class Pallet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'pallets')]
    private ?Truck $truck = null;

    #[ORM\ManyToOne(inversedBy: 'pallets')]
    private ?Associate $associate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTruck(): ?Truck
    {
        return $this->truck;
    }

    public function setTruck(?Truck $truck): static
    {
        $this->truck = $truck;

        return $this;
    }

    public function getAssociate(): ?Associate
    {
        return $this->associate;
    }

    public function setAssociate(?Associate $associate): static
    {
        $this->associate = $associate;

        return $this;
    }
}
