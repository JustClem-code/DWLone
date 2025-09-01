<?php

namespace App\Entity;

use App\Repository\LocationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LocationRepository::class)]
class Location
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToOne(mappedBy: 'location', cascade: ['persist', 'remove'])]
    private ?Bag $bag = null;

    /**
     * @var Collection<int, Package>
     */
    #[ORM\OneToMany(targetEntity: Package::class, mappedBy: 'location')]
    private Collection $packages;

    public function __construct()
    {
        $this->packages = new ArrayCollection();
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

    public function getBag(): ?Bag
    {
        return $this->bag;
    }

    public function setBag(?Bag $bag): static
    {
        // unset the owning side of the relation if necessary
        if ($bag === null && $this->bag !== null) {
            $this->bag->setLocation(null);
        }

        // set the owning side of the relation if necessary
        if ($bag !== null && $bag->getLocation() !== $this) {
            $bag->setLocation($this);
        }

        $this->bag = $bag;

        return $this;
    }

    /**
     * @return Collection<int, Package>
     */
    public function getPackages(): Collection
    {
        return $this->packages;
    }

    public function addPackage(Package $package): static
    {
        if (!$this->packages->contains($package)) {
            $this->packages->add($package);
            $package->setLocation($this);
        }

        return $this;
    }

    public function removePackage(Package $package): static
    {
        if ($this->packages->removeElement($package)) {
            // set the owning side to null (unless already changed)
            if ($package->getLocation() === $this) {
                $package->setLocation(null);
            }
        }

        return $this;
    }
}
