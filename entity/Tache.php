<?php

class Tache
{
    private int $idTache;
    private string $titre;
    private string $description;
    private ?string $dateDebut;
    private ?string $dateFin;
    private int $priorite;
    private string $statut;
    private ?string $dateCreation;
    private ?string $dateModification;

    public function __construct(
        int $idTache,
        string $titre,
        string $description,
        ?string $dateDebut,
        ?string $dateFin,
        int $priorite,
        string $statut,
        ?string $dateCreation,
        ?string $dateModification
    ) {
        $this->idTache          = $idTache;
        $this->titre            = $titre;
        $this->description      = $description;
        $this->dateDebut        = $dateDebut;
        $this->dateFin          = $dateFin;
        $this->priorite         = $priorite;
        $this->statut           = $statut;
        $this->dateCreation     = $dateCreation;
        $this->dateModification = $dateModification;
    }

    // Getters et setters
    public function getIdTache(): int
    {
        return $this->idTache;
    }

    public function setIdTache(int $idTache): void
    {
        $this->idTache = $idTache;
    }

    public function getTitre(): string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): void
    {
        $this->titre = $titre;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getDateDebut(): ?string
    {
        return $this->dateDebut;
    }

    public function setDateDebut(?string $dateDebut): void
    {
        $this->dateDebut = $dateDebut;
    }

    public function getDateFin(): ?string
    {
        return $this->dateFin;
    }

    public function setDateFin(?string $dateFin): void
    {
        $this->dateFin = $dateFin;
    }

    public function getPriorite(): int
    {
        return $this->priorite;
    }

    public function setPriorite(int $priorite): void
    {
        $this->priorite = $priorite;
    }

    public function getStatut(): string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): void
    {
        $this->statut = $statut;
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
