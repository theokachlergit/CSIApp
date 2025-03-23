<?php
class Produit
{
    private int $id;
    private string $nom;
    private float $prix;
    private string $categorie;
    private int $stock;

    public function __construct(int $id, string $nom, float $prix, string $categorie, int $stock)
    {
        $this->id = $id;
        $this->nom = $nom;
        $this->prix = $prix;
        $this->categorie = $categorie;
        $this->stock = $stock;
    }

    public function getId(): int
    {
        return $this->id;
    }
    public function getNom(): string
    {
        return $this->nom;
    }
    public function getPrix(): float
    {
        return $this->prix;
    }
    public function getCategorie(): string
    {
        return $this->categorie;
    }
    public function getStock(): int
    {
        return $this->stock;
    }

    public static function getAllProducts() : array{
        // Récupération des produits depuis la BDD
        try {
            $stmt = Database::getConn()->query("SELECT * FROM produit");
            $rawProduits = $stmt->fetchAll();
        } catch (PDOException $e) {
            die("Erreur : " . $e->getMessage());
        }
        $produits = [];
        foreach ($rawProduits as $rawProduit){
            $produits[] = new Produit($rawProduit['idProduit'], $rawProduit['libelleProduit'], $rawProduit['prixUnitaire'], $rawProduit['typeProduit'], $rawProduit['quantiteStock']);
        }
        return $produits;
    }
}
