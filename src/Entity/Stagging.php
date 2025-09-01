<?php

namespace App\Entity;

use App\Repository\StaggingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StaggingRepository::class)]
class Stagging
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToOne(mappedBy: 'stagging', cascade: ['persist', 'remove'])]
    private ?Road $road = null;

    /**
     * @var Collection<int, Cart>
     */
    #[ORM\OneToMany(targetEntity: Cart::class, mappedBy: 'stagging')]
    private Collection $carts;

    public function __construct()
    {
        $this->carts = new ArrayCollection();
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

    public function getRoad(): ?Road
    {
        return $this->road;
    }

    public function setRoad(?Road $road): static
    {
        // unset the owning side of the relation if necessary
        if ($road === null && $this->road !== null) {
            $this->road->setStagging(null);
        }

        // set the owning side of the relation if necessary
        if ($road !== null && $road->getStagging() !== $this) {
            $road->setStagging($this);
        }

        $this->road = $road;

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
            $cart->setStagging($this);
        }

        return $this;
    }

    public function removeCart(Cart $cart): static
    {
        if ($this->carts->removeElement($cart)) {
            // set the owning side to null (unless already changed)
            if ($cart->getStagging() === $this) {
                $cart->setStagging(null);
            }
        }

        return $this;
    }
}
