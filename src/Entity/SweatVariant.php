<?php

namespace App\Entity;

use App\Repository\SweatVariantRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SweatVariantRepository::class)]
class SweatVariant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $stock = null;

    #[ORM\ManyToOne(inversedBy: 'sweatVariants')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Sweat $sweat = null;

    #[ORM\ManyToOne(inversedBy: 'sweatVariants')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Size $size = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): static
    {
        $this->stock = $stock;

        return $this;
    }

    public function getSweat(): ?Sweat
    {
        return $this->sweat;
    }

    public function setSweat(?Sweat $sweat): static
    {
        $this->sweat = $sweat;

        return $this;
    }

    public function getSize(): ?Size
    {
        return $this->size;
    }

    public function setSize(?Size $size): static
    {
        $this->size = $size;

        return $this;
    }
}
