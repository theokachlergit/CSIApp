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
    public function getPrixUnitaire(): float
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

    public static function mettreAJourStock(int $id, int $qte){
        try{
            if (!Database::getConn()->inTransaction()){
                Database::getConn()->beginTransaction();
            }
            $produit = Produit::getProductById($id);
            if ($qte <= $produit->stock) {
                $stmt = Database::getConn()->prepare("UPDATE produit SET quantiteStock = $qte WHERE idProduit = $id");
                $stmt->execute();
                Database::getConn()->commit();
            }else{
                Database::getConn()->rollBack();
                echo "<script>alert('La qte vente doit être inférieure ou égale à la qte en stock')</script>";
            }
        }catch (PDOException $e){
            die("Erreur : " . $e->getMessage());
        }
    }

    public static function getAllProducts() : array{
        // Récupération des produits depuis la BDD
        try {
            $stmt = Database::getConn()->query("SELECT * FROM produit");
            $rawProduits = $stmt->fetchAll();
            $produits = [];
            foreach ($rawProduits as $rawProduit){
                $produits[] = new Produit($rawProduit['idProduit'], $rawProduit['libelleProduit'], $rawProduit['prixUnitaire'], $rawProduit['typeProduit'], $rawProduit['quantiteStock']);
            }
            return $produits;
        } catch (PDOException $e) {
            die("Erreur : " . $e->getMessage());
        }
    }

    public static function getProductById(int $id) : Produit{
        try{
            $stmt = Database::getConn()->prepare("SELECT * FROM produit WHERE idProduit = $id");
            $stmt->execute();
            $rawProduit = $stmt->fetch();

            return new Produit($rawProduit['idProduit'], $rawProduit['libelleProduit'], $rawProduit['prixUnitaire'], $rawProduit['typeProduit'], $rawProduit['quantiteStock']);

        }catch (PDOException $e){
            die("Erreur : " . $e->getMessage());
        }
    }
}
