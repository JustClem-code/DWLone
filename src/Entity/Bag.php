<?php

namespace App\Entity;

use App\Repository\BagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BagRepository::class)]
class Bag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'bags')]
    private ?road $road = null;

    #[ORM\Column]
    private ?bool $damaged = null;

    #[ORM\OneToOne(inversedBy: 'bag', cascade: ['persist', 'remove'])]
    private ?Location $location = null;

    /**
     * @var Collection<int, Package>
     */
    #[ORM\OneToMany(targetEntity: Package::class, mappedBy: 'bag')]
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

    public function getRoad(): ?road
    {
        return $this->road;
    }

    public function setRoad(?road $road): static
    {
        $this->road = $road;

        return $this;
    }

    public function isDamaged(): ?bool
    {
        return $this->damaged;
    }

    public function setDamaged(bool $damaged): static
    {
        $this->damaged = $damaged;

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
            $package->setBag($this);
        }

        return $this;
    }

    public function removePackage(Package $package): static
    {
        if ($this->packages->removeElement($package)) {
            // set the owning side to null (unless already changed)
            if ($package->getBag() === $this) {
                $package->setBag(null);
            }
        }

        return $this;
    }
}
