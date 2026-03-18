<?php

namespace App\Entity;

use App\Repository\PostcodesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostcodesRepository::class)]
class Postcodes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'postcodes')]
    private ?GroupPostcodes $groupPostcodes = null;

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

    public function getGroupPostcodes(): ?GroupPostcodes
    {
        return $this->groupPostcodes;
    }

    public function setGroupPostcodes(?GroupPostcodes $groupPostcodes): static
    {
        $this->groupPostcodes = $groupPostcodes;

        return $this;
    }
}
