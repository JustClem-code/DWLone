<?php

namespace App\Entity;

use App\Repository\GroupPostcodesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GroupPostcodesRepository::class)]
class GroupPostcodes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, Postcodes>
     */
    #[ORM\OneToMany(targetEntity: Postcodes::class, mappedBy: 'groupPostcodes')]
    private Collection $postcodes;

    public function __construct()
    {
        $this->postcodes = new ArrayCollection();
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
     * @return Collection<int, Postcodes>
     */
    public function getPostcodes(): Collection
    {
        return $this->postcodes;
    }

    public function addPostcode(Postcodes $postcode): static
    {
        if (!$this->postcodes->contains($postcode)) {
            $this->postcodes->add($postcode);
            $postcode->setGroupPostcodes($this);
        }

        return $this;
    }

    public function removePostcode(Postcodes $postcode): static
    {
        if ($this->postcodes->removeElement($postcode)) {
            // set the owning side to null (unless already changed)
            if ($postcode->getGroupPostcodes() === $this) {
                $postcode->setGroupPostcodes(null);
            }
        }

        return $this;
    }
}
