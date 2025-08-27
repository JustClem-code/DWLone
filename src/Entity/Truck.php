<?php

namespace App\Entity;

use App\Repository\TruckRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TruckRepository::class)]
class Truck
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 7)]
    private ?string $Wrid = null;

    #[ORM\Column]
    private ?\DateTime $ExpectedDate = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $DeliveryDate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWrid(): ?string
    {
        return $this->Wrid;
    }

    public function setWrid(string $Wrid): static
    {
        $this->Wrid = $Wrid;

        return $this;
    }

    public function getExpectedDate(): ?\DateTime
    {
        return $this->ExpectedDate;
    }

    public function setExpectedDate(\DateTime $ExpectedDate): static
    {
        $this->ExpectedDate = $ExpectedDate;

        return $this;
    }

    public function getDeliveryDate(): ?\DateTime
    {
        return $this->DeliveryDate;
    }

    public function setDeliveryDate(?\DateTime $DeliveryDate): static
    {
        $this->DeliveryDate = $DeliveryDate;

        return $this;
    }
}
