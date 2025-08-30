<?php

namespace App\Entity;

use App\Repository\RoleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoleRepository::class)]
class Role
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, Associate>
     */
    #[ORM\OneToMany(targetEntity: Associate::class, mappedBy: 'role')]
    private Collection $associates;

    public function __construct()
    {
        $this->associates = new ArrayCollection();
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

    /**
     * @return Collection<int, Associate>
     */
    public function getAssociates(): Collection
    {
        return $this->associates;
    }

    public function addAssociate(Associate $associate): static
    {
        if (!$this->associates->contains($associate)) {
            $this->associates->add($associate);
            $associate->setRole($this);
        }

        return $this;
    }

    public function removeAssociate(Associate $associate): static
    {
        if ($this->associates->removeElement($associate)) {
            // set the owning side to null (unless already changed)
            if ($associate->getRole() === $this) {
                $associate->setRole(null);
            }
        }

        return $this;
    }
}
