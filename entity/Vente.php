<?php

class Vente
{
    private ?int $idVente;
    private float $prixVente;
    private EMethodePaiement $methodePaiement;
    private string $emailWoofer;
    private array $produits; // idProduit => quantiteVente

    public function __construct(?int $id, float $prix, EMethodePaiement $methodePaiement, string $emailWoofer, array $produits)
    {
        $this->idVente = $id;
        $this->prixVente = $prix;
        $this->methodePaiement = $methodePaiement;
        $this->emailWoofer = $emailWoofer;
        $this->produits = $produits;
    }

    public function enregistrerVente() : void{
        try {
            Database::getConn()->beginTransaction();
            $stmt = Database::getConn()->prepare("INSERT INTO vente (prixVente, methodePaiement, emailWoofer) VALUES ($this->prixVente, '{$this->methodePaiement->value}','$this->emailWoofer')");
            $stmt->execute();

            $this->idVente = Database::getConn()->lastInsertId();
            foreach ($this->produits as $produit => $qte) {
                $stmt = Database::getConn()->prepare("INSERT INTO estVendu VALUES ($produit, $this->idVente, $qte)");
                $stmt->execute();

                Produit::mettreAJourStock($produit, $qte);
            }
        }catch (PDOException $e){
            die("Erreur : " . $e->getMessage());
        }
    }

}