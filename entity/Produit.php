<?php

class Produit
{
    public int $idProduit;
    public enumTypeProduit $typeProduit;
    public string $libelle;
    public int $quantiteStock;
    public int $prixUnitaire;


    public function __construct($idProduit, $typeProduit, $libelle, $quantiteStock, $prixUnitaire)
    {
        $this->idProduit = $idProduit;
        $this->typeProduit = $typeProduit;
        $this->libelle = $libelle;
        $this->quantiteStock = $quantiteStock;
        $this->prixUnitaire = $prixUnitaire;
    }

    public function mettreAJourStock(int $qte, $id): void
    {
        
    }

    function verifierStock(): int
    {
        return $this->quantiteStock > 0;
    }
}

