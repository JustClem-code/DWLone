<?php

namespace App\Entity;

use App\Repository\DeliveryCompanyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DeliveryCompanyRepository::class)]
class DeliveryCompany
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, DeliveryPerson>
     */
    #[ORM\OneToMany(targetEntity: DeliveryPerson::class, mappedBy: 'company', orphanRemoval: true)]
    private Collection $deliveryPeople;

    public function __construct()
    {
        $this->deliveryPeople = new ArrayCollection();
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
     * @return Collection<int, DeliveryPerson>
     */
    public function getDeliveryPeople(): Collection
    {
        return $this->deliveryPeople;
    }

    public function addDeliveryPerson(DeliveryPerson $deliveryPerson): static
    {
        if (!$this->deliveryPeople->contains($deliveryPerson)) {
            $this->deliveryPeople->add($deliveryPerson);
            $deliveryPerson->setCompany($this);
        }

        return $this;
    }

    public function removeDeliveryPerson(DeliveryPerson $deliveryPerson): static
    {
        if ($this->deliveryPeople->removeElement($deliveryPerson)) {
            // set the owning side to null (unless already changed)
            if ($deliveryPerson->getCompany() === $this) {
                $deliveryPerson->setCompany(null);
            }
        }

        return $this;
    }
}
