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
    private ?Road $road = null;

    #[ORM\ManyToOne(inversedBy: 'carts')]
    private ?Stagging $stagging = null;

    #[ORM\ManyToOne(inversedBy: 'carts')]
    private ?Associate $associate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRoad(): ?Road
    {
        return $this->road;
    }

    public function setRoad(?Road $road): static
    {
        $this->road = $road;

        return $this;
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
