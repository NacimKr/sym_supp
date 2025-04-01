<?php

namespace App\Entity;

use App\Repository\PokemonRepository;
use App\Validator\BanWord;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Au niveau des contraintes on peut les créer nous meme avec la command
 * make:validator
 * 
 * Il va créer un dossier Validator
 * Et des entite
 */


#[ORM\Entity(repositoryClass: PokemonRepository::class)]
//Si on souhaite avoir plusieurs champs on passer un tableau
#[UniqueEntity('name')]
class Pokemon
{
    //Nous avons des attributs qui permettent à l'ORM 
    //de dire comment les données seront sauvegardées en BDD
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['pokemons.index'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)] //Indique c'est une colonne de notre BDD et qui pour taille 255 caractères
    //La bonne pratique c'est de faire les assert validation au niveau de l'entity et non sur le formulaire
    #[Assert\NotBlank()]
    #[Assert\Length(                        
        min: 3,
        max: 50,
        minMessage : "Le nom est trop court 3 minimum", 
        maxMessage : "Le nom est trop long 10 maximum"
    )]
    #[Assert\Regex(                       
        '/[a-z]/',
        message: 'Le nom ne doit pas comporter de chiffre'
    )]
    #[BanWord()]
    #[Groups(['pokemons.index'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)] //Indique que c'est un champs de type text
    #[Assert\NotBlank()] // Il va utiliser les valeurs qu'on a mis par defait dans notre validator dans le dossier Validator BanWord.php
    #[Groups(['pokemons.show'])]
    private ?string $content = null;

    #[ORM\Column]
    #[Groups(['pokemons.index'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    #[Groups(['pokemons.index'])]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column]
    #[Assert\NotBlank()]
    #[Assert\LessThan(
        value: 80,
    )]
    #[Assert\Positive()]
    private ?int $pv = null;

    #[ORM\Column(length: 150)]
    private ?string $slug = null;

    #[ORM\ManyToOne(inversedBy: 'pokemon')]
    private ?Dresseur $dresseur = null;

    //cascade persist pour persister un objet automatiquement sans faire $em->persist()
    #[ORM\ManyToOne(inversedBy: 'pokemon', cascade:['persist'])]
    #[Groups(['pokemons.show'])]
    private ?Type $type = null;

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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

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

    public function getPv(): ?int
    {
        return $this->pv;
    }

    public function setPv(int $pv): static
    {
        $this->pv = $pv;

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

    public function getDresseur(): ?Dresseur
    {
        return $this->dresseur;
    }

    public function setDresseur(?Dresseur $dresseur): static
    {
        $this->dresseur = $dresseur;

        return $this;
    }

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(?Type $type): static
    {
        $this->type = $type;

        return $this;
    }
}
