<?php

namespace App\Entity;

use App\Repository\AisleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AisleRepository::class)]
class Aisle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?bool $enabled = null;

    /**
     * @var Collection<int, Location>
     */
    #[ORM\OneToMany(targetEntity: Location::class, mappedBy: 'aisle', orphanRemoval: true)]
    private Collection $locations;

    /**
     * @var Collection<int, GroupPostcodes>
     */
    #[ORM\OneToMany(targetEntity: GroupPostcodes::class, mappedBy: 'Aisle')]
    private Collection $groupPostcodes;

    public function __construct()
    {
        $this->locations = new ArrayCollection();
        $this->groupPostcodes = new ArrayCollection();
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

    public function isEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): static
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * @return Collection<int, Location>
     */
    public function getLocations(): Collection
    {
        return $this->locations;
    }

    public function addLocation(Location $location): static
    {
        if (!$this->locations->contains($location)) {
            $this->locations->add($location);
            $location->setAisle($this);
        }

        return $this;
    }

    public function removeLocation(Location $location): static
    {
        if ($this->locations->removeElement($location)) {
            // set the owning side to null (unless already changed)
            if ($location->getAisle() === $this) {
                $location->setAisle(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, GroupPostcodes>
     */
    public function getGroupPostcodes(): Collection
    {
        return $this->groupPostcodes;
    }

    public function addGroupPostcode(GroupPostcodes $groupPostcode): static
    {
        if (!$this->groupPostcodes->contains($groupPostcode)) {
            $this->groupPostcodes->add($groupPostcode);
            $groupPostcode->setAisle($this);
        }

        return $this;
    }

    public function removeGroupPostcode(GroupPostcodes $groupPostcode): static
    {
        if ($this->groupPostcodes->removeElement($groupPostcode)) {
            // set the owning side to null (unless already changed)
            if ($groupPostcode->getAisle() === $this) {
                $groupPostcode->setAisle(null);
            }
        }

        return $this;
    }
}
