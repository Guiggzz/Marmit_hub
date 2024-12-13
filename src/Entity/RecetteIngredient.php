<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class RecetteIngredient
{
    // Propriété primaire (ID)
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Relation ManyToOne avec Recette
    #[ORM\ManyToOne(targetEntity: Recette::class, inversedBy: 'recetteIngredients')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Recette $recette = null;

    // Relation ManyToOne avec Ingredient
    #[ORM\ManyToOne(targetEntity: Ingredient::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Ingredient $ingredient = null;

    // Propriété pour la quantité associée à l'ingrédient
    #[ORM\Column(type: 'integer')]
    private ?int $quantite = null;

    // Getter pour l'ID
    public function getId(): ?int
    {
        return $this->id;
    }

    // Getter et setter pour Recette
    public function getRecette(): ?Recette
    {
        return $this->recette;
    }

    public function setRecette(?Recette $recette): static
    {
        $this->recette = $recette;
        return $this;
    }

    // Getter et setter pour Ingredient
    public function getIngredient(): ?Ingredient
    {
        return $this->ingredient;
    }

    public function setIngredient(?Ingredient $ingredient): static
    {
        $this->ingredient = $ingredient;
        return $this;
    }

    // Getter et setter pour Quantite
    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;
        return $this;
    }
}
