<?php

namespace App\Entity;

use App\Repository\RoadRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoadRepository::class)]
class Road
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToOne(inversedBy: 'road', cascade: ['persist', 'remove'])]
    private ?Stagging $stagging = null;

    /**
     * @var Collection<int, Cart>
     */
    #[ORM\OneToMany(targetEntity: Cart::class, mappedBy: 'road')]
    private Collection $carts;

    /**
     * @var Collection<int, RoadPart>
     */
    #[ORM\OneToMany(targetEntity: RoadPart::class, mappedBy: 'roadId', orphanRemoval: true)]
    private Collection $roadParts;

    public function __construct()
    {
        $this->carts = new ArrayCollection();
        $this->roadParts = new ArrayCollection();
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

    public function getStagging(): ?Stagging
    {
        return $this->stagging;
    }

    public function setStagging(?Stagging $stagging): static
    {
        $this->stagging = $stagging;

        return $this;
    }

    /**
     * @return Collection<int, Cart>
     */
    public function getCarts(): Collection
    {
        return $this->carts;
    }

    public function addCart(Cart $cart): static
    {
        if (!$this->carts->contains($cart)) {
            $this->carts->add($cart);
            $cart->setRoad($this);
        }

        return $this;
    }

    public function removeCart(Cart $cart): static
    {
        if ($this->carts->removeElement($cart)) {
            // set the owning side to null (unless already changed)
            if ($cart->getRoad() === $this) {
                $cart->setRoad(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, RoadPart>
     */
    public function getRoadParts(): Collection
    {
        return $this->roadParts;
    }

    public function addRoadPart(RoadPart $roadPart): static
    {
        if (!$this->roadParts->contains($roadPart)) {
            $this->roadParts->add($roadPart);
            $roadPart->setRoadId($this);
        }

        return $this;
    }

    public function removeRoadPart(RoadPart $roadPart): static
    {
        if ($this->roadParts->removeElement($roadPart)) {
            // set the owning side to null (unless already changed)
            if ($roadPart->getRoadId() === $this) {
                $roadPart->setRoadId(null);
            }
        }

        return $this;
    }
}
