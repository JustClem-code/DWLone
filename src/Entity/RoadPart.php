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
    private ?Road $roadId = null;

    #[ORM\ManyToOne(inversedBy: 'roadParts')]
    private ?User $userId = null;

    #[ORM\Column]
    private ?bool $stagged = null;

    /**
     * @var Collection<int, Bag>
     */
    #[ORM\OneToMany(targetEntity: Bag::class, mappedBy: 'roadPartId')]
    private Collection $bags;

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

    public function getRoadId(): ?Road
    {
        return $this->roadId;
    }

    public function setRoadId(?Road $roadId): static
    {
        $this->roadId = $roadId;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->userId;
    }

    public function setUserId(?User $userId): static
    {
        $this->userId = $userId;

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
            $bag->setRoadPartId($this);
        }

        return $this;
    }

    public function removeBag(Bag $bag): static
    {
        if ($this->bags->removeElement($bag)) {
            // set the owning side to null (unless already changed)
            if ($bag->getRoadPartId() === $this) {
                $bag->setRoadPartId(null);
            }
        }

        return $this;
    }
}
