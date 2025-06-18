<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: RecipeRepository::class)]
#[UniqueEntity('title')]
#[UniqueEntity('slug')]
class Recipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('read')]
    private ?int $id = null;
    
    
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank()]
    #[Assert\Length(min: 15)]
    #[Groups('read')]
    private ?string $title = null;
    
    #[ORM\Column(length: 255)]
    #[Groups('read')]
    private ?string $slug = null;
    
    #[ORM\Column(type: Types::TEXT)]
    #[Groups('read')]
    private ?string $content = null;
    
    #[ORM\Column]
    #[Groups('read')]
    private ?\DateTimeImmutable $createAt = null;
    
    #[ORM\Column]
    #[Groups('read')]
    private ?\DateTimeImmutable $updatedAt = null;
    
    #[ORM\Column(nullable: true)]
    #[Assert\NotBlank()]
    #[Assert\Positive()]
    #[Groups('read')]
    private ?int $duration = null;

    #[ORM\ManyToOne(inversedBy: 'recipes', cascade : ['persist'])] // s'il y'a un objet il le persistera de façon automatique
    private ?Category $category = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $brochureFilename = null;

    /**
     * @var Collection<int, Quantity>
     */
    #[ORM\OneToMany(targetEntity: Quantity::class, mappedBy: 'recipe')]
    private Collection $quantities;

    public function __construct() {
        $this->createAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
        $this->quantities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeImmutable
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeImmutable $createAt): static
    {
        $this->createAt = $createAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getBrochureFilename(): ?string
    {
        return $this->brochureFilename;
    }

    public function setBrochureFilename(?string $brochureFilename): static
    {
        $this->brochureFilename = $brochureFilename;

        return $this;
    }

    /**
     * @return Collection<int, Quantity>
     */
    public function getQuantities(): Collection
    {
        return $this->quantities;
    }

    public function addQuantity(Quantity $quantity): static
    {
        if (!$this->quantities->contains($quantity)) {
            $this->quantities->add($quantity);
            $quantity->setRecipe($this);
        }

        return $this;
    }

    public function removeQuantity(Quantity $quantity): static
    {
        if ($this->quantities->removeElement($quantity)) {
            // set the owning side to null (unless already changed)
            if ($quantity->getRecipe() === $this) {
                $quantity->setRecipe(null);
            }
        }

        return $this;
    }
}
