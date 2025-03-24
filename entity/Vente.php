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
            foreach ($this->produits as $produitId => $qte) {
                $stmt = Database::getConn()->prepare("INSERT INTO estVendu VALUES ($produitId, $this->idVente, $qte)");
                $stmt->execute();

                $produit = Produit::getProductById($produitId);

                if ($qte <= $produit->getQuantiteStock()) {
                    Produit::mettreAJourStock($produitId, $qte);
                }else{
                    Database::getConn()->rollBack();
                    echo "<script>alert('La qte vente doit être inférieure ou égale à la qte en stock')</script>";
            }

            }
        }catch (PDOException $e){
            die("Erreur : " . $e->getMessage());
        }
    }

    public static function getAllVentes() : array{
        // Récupération des produits depuis la BDD
        try {
            $stmt = Database::getConn()->query("SELECT * FROM vente 
    inner join estVendu on vente.idVente = estVendu.idVente
    inner join produit on estVendu.idProduit = produit.idProduit");
            $rawVentes = $stmt->fetchAll();
        } catch (PDOException $e) {
            die("Erreur : " . $e->getMessage());
        }
        $ventes = [];
        foreach ($rawVentes as $rawVente) {
            if (!isset($ventes[$rawVente['idVente']])) {
                $ventes[$rawVente['idVente']] = new Vente($rawVente['idVente'], $rawVente['prixVente'], EMethodePaiement::from($rawVente['methodePaiement']), $rawVente['emailWoofer'], []);
            }

            $ventes[$rawVente['idVente']]->produits[] = [
                'idProduit' => $rawVente['idProduit'],
                'libelleProduit' => $rawVente['libelleProduit'],
                'quantiteVente' => $rawVente['quantiteVente']
            ];
        }
        return $ventes;
    }

    public function getIdVente(): int
    {
        return $this->idVente;
    }

    public function getPrixVente(): float
    {
        return $this->prixVente;
    }

    public function getEmailWoofer(): string
    {
        return $this->emailWoofer;
    }

    public function getMethodePaiement(): EMethodePaiement
    {
        return $this->methodePaiement;
    }

    public function getProduits(): array
    {
        return $this->produits;
    }


}