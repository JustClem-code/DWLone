<?php

namespace App\Entity;

use App\Repository\AssociateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AssociateRepository::class)]
class Associate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\Column(length: 255)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    #[ORM\ManyToOne(inversedBy: 'associates')]
    #[ORM\JoinColumn(nullable: false)]
    private ?role $role = null;

    /**
     * @var Collection<int, Pallet>
     */
    #[ORM\OneToMany(targetEntity: Pallet::class, mappedBy: 'associate')]
    private Collection $pallets;

    public function __construct()
    {
        $this->pallets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getRole(): ?role
    {
        return $this->role;
    }

    public function setRole(?role $role): static
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return Collection<int, Pallet>
     */
    public function getPallets(): Collection
    {
        return $this->pallets;
    }

    public function addPallet(Pallet $pallet): static
    {
        if (!$this->pallets->contains($pallet)) {
            $this->pallets->add($pallet);
            $pallet->setAssociate($this);
        }

        return $this;
    }

    public function removePallet(Pallet $pallet): static
    {
        if ($this->pallets->removeElement($pallet)) {
            // set the owning side to null (unless already changed)
            if ($pallet->getAssociate() === $this) {
                $pallet->setAssociate(null);
            }
        }

        return $this;
    }
}
