<?php

namespace App\Entity;

use App\Repository\QuantityRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuantityRepository::class)]
class Quantity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $unit = null;

    #[ORM\ManyToOne(inversedBy: 'quantities')]
    private ?Recipe $recipe = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUnit(): ?int
    {
        return $this->unit;
    }

    public function setUnit(int $unit): static
    {
        $this->unit = $unit;

        return $this;
    }

    public function getRecipe(): ?Recipe
    {
        return $this->recipe;
    }

    public function setRecipe(?Recipe $recipe): static
    {
        $this->recipe = $recipe;

        return $this;
    }
}
