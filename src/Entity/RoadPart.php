<?php

namespace App\Entity;

use App\Repository\RoadPartRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoadPartRepository::class)]
class RoadPart
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $number = null;

    #[ORM\ManyToOne(inversedBy: 'roadParts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Road $road = null;

    #[ORM\ManyToOne(inversedBy: 'roadParts')]
    private ?User $user = null;

    #[ORM\Column]
    private ?bool $stagged = null;

    /**
     * @var Collection<int, Bag>
     */
    #[ORM\OneToMany(targetEntity: Bag::class, mappedBy: 'roadPart')]
    private Collection $bags;

    #[ORM\OneToOne(mappedBy: 'roadPart', cascade: ['persist', 'remove'])]
    private ?Cart $cart = null;

    public function __construct()
    {
        $this->bags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): static
    {
        $this->number = $number;

        return $this;
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function isStagged(): ?bool
    {
        return $this->stagged;
    }

    public function setStagged(bool $stagged): static
    {
        $this->stagged = $stagged;

        return $this;
    }

    /**
     * @return Collection<int, Bag>
     */
    public function getBags(): Collection
    {
        return $this->bags;
    }

    public function addBag(Bag $bag): static
    {
        if (!$this->bags->contains($bag)) {
            $this->bags->add($bag);
            $bag->setRoadPart($this);
        }

        return $this;
    }

    public function removeBag(Bag $bag): static
    {
        if ($this->bags->removeElement($bag)) {
            // set the owning side to null (unless already changed)
            if ($bag->getRoadPart() === $this) {
                $bag->setRoadPart(null);
            }
        }

        return $this;
    }

    public function getCart(): ?Cart
    {
        return $this->cart;
    }

    public function setCart(?Cart $cart): static
    {
        // unset the owning side of the relation if necessary
        if ($cart === null && $this->cart !== null) {
            $this->cart->setRoadPart(null);
        }

        // set the owning side of the relation if necessary
        if ($cart !== null && $cart->getRoadPart() !== $this) {
            $cart->setRoadPart($this);
        }

        $this->cart = $cart;

        return $this;
    }
}
