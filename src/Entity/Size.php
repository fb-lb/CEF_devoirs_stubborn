<?php

namespace App\Entity;

use App\Repository\SizeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SizeRepository::class)]
class Size
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $size = null;

    /**
     * @var Collection<int, SweatVariant>
     */
    #[ORM\OneToMany(targetEntity: SweatVariant::class, mappedBy: 'size', orphanRemoval: true)]
    private Collection $sweatVariants;

    public function __construct()
    {
        $this->sweatVariants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSize(): ?string
    {
        return $this->size;
    }

    public function setSize(string $size): static
    {
        $this->size = $size;

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
            $sweatVariant->setSize($this);
        }

        return $this;
    }

    public function removeSweatVariant(SweatVariant $sweatVariant): static
    {
        if ($this->sweatVariants->removeElement($sweatVariant)) {
            // set the owning side to null (unless already changed)
            if ($sweatVariant->getSize() === $this) {
                $sweatVariant->setSize(null);
            }
        }

        return $this;
    }
}
