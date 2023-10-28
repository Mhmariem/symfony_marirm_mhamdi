<?php

namespace App\Entity;

use App\Repository\CarRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CarRepository::class)]
class Car
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(length: 50)]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'car', targetEntity: Showroom::class)]
    private Collection $showrooms;

    public function __construct()
    {
        $this->showrooms = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Showroom>
     */
    public function getShowrooms(): Collection
    {
        return $this->showrooms;
    }

    public function addShowroom(Showroom $showroom): static
    {
        if (!$this->showrooms->contains($showroom)) {
            $this->showrooms->add($showroom);
            $showroom->setCar($this);
        }

        return $this;
    }

    public function removeShowroom(Showroom $showroom): static
    {
        if ($this->showrooms->removeElement($showroom)) {
            // set the owning side to null (unless already changed)
            if ($showroom->getCar() === $this) {
                $showroom->setCar(null);
            }
        }

        return $this;
    }
}
