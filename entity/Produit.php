<?php

class Product
{
    private int $idProduct;
    private string $name;
    private float $price;
    private string $description;
    private ?string $dateCreation;
    private ?string $dateModification;

    public function __construct(
        int $idProduct,
        string $name,
        float $price,
        string $description,
        ?string $dateCreation,
        ?string $dateModification
    ) {
        $this->idProduct         = $idProduct;
        $this->name              = $name;
        $this->price             = $price;
        $this->description       = $description;
        $this->dateCreation      = $dateCreation;
        $this->dateModification  = $dateModification;
    }

    // Getters et setters
    public function getIdProduct(): int
    {
        return $this->idProduct;
    }

    public function setIdProduct(int $idProduct): void
    {
        $this->idProduct = $idProduct;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getDateCreation(): ?string
    {
        return $this->dateCreation;
    }

    public function setDateCreation(?string $dateCreation): void
    {
        $this->dateCreation = $dateCreation;
    }

    public function getDateModification(): ?string
    {
        return $this->dateModification;
    }

    public function setDateModification(?string $dateModification): void
    {
        $this->dateModification = $dateModification;
    }
}
