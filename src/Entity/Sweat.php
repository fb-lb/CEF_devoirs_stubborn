<?php

namespace App\Entity;

use App\Repository\SweatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SweatRepository::class)]
class Sweat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column]
    private ?bool $top = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $file_name = null;

    /**
     * @var Collection<int, SweatVariant>
     */
    #[ORM\OneToMany(targetEntity: SweatVariant::class, mappedBy: 'sweat', orphanRemoval: true)]
    private Collection $sweatVariants;

    public function __construct()
    {
        $this->sweatVariants = new ArrayCollection();
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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function isTop(): ?bool
    {
        return $this->top;
    }

    public function setTop(bool $top): static
    {
        $this->top = $top;

        return $this;
    }

    public function getFileName(): ?string
    {
        return $this->file_name;
    }

    public function setFileName(?string $file_name): static
    {
        $this->file_name = $file_name;

        return $this;
    }

    /**
     * @return Collection<int, SweatVariant>
     */
    public function getSweatVariants(): Collection
    {
        return $this->sweatVariants;
    }

    public function addSweatVariant(SweatVariant $sweatVariant): static
    {
        if (!$this->sweatVariants->contains($sweatVariant)) {
            $this->sweatVariants->add($sweatVariant);
            $sweatVariant->setSweat($this);
        }

        return $this;
    }

    public function removeSweatVariant(SweatVariant $sweatVariant): static
    {
        if ($this->sweatVariants->removeElement($sweatVariant)) {
            // set the owning side to null (unless already changed)
            if ($sweatVariant->getSweat() === $this) {
                $sweatVariant->setSweat(null);
            }
        }

        return $this;
    }
}
