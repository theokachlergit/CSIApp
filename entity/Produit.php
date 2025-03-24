<?php


require '../enum/enumTypeProduit.php';
class Produit
{
    private int $id;
    private string $nom;
    private float $prix;
    private enumTypeProduit $type;
    private int $quantiteStock;

    public function __construct(int $id, string $nom, float $prix, enumTypeProduit $type, int $stock)
    {
        $this->id = $id;
        $this->nom = $nom;
        $this->prix = $prix;
        $this->type = $type;
        $this->quantiteStock = $stock;
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
    public function getType(): enumTypeProduit
    {
        return $this->type;
    }
    public function getQuantiteStock(): int
    {
        return $this->quantiteStock;
    }

    public static function mettreAJourStock(int $id, int $qte){
        try{
            if (!Database::getConn()->inTransaction()){
                Database::getConn()->beginTransaction();
            }
            $produit = Produit::getProductById($id);
            if ($qte <= $produit->quantiteStock) {
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
    public static function mettreAJourNom(int $id, string $nom){
        try{
            $stmt = Database::getConn()->prepare("UPDATE produit SET libelleProduit = '$nom' WHERE idProduit = $id");
            $stmt->execute();
        }catch (PDOException $e){
            die("Erreur : " . $e->getMessage());
        }
    }

    public static function mettreAJourPrix(int $id, float $prix){
        try{
            $stmt = Database::getConn()->prepare("UPDATE produit SET prixUnitaire = $prix WHERE idProduit = $id");
            $stmt->execute();
        }catch (PDOException $e){
            die("Erreur : " . $e->getMessage());
        }
    }

    public static function mettreAJourType(int $id, string $type){
        try{
            $stmt = Database::getConn()->prepare("UPDATE produit SET typeProduit = '$type' WHERE idProduit = $id");
            $stmt->execute();
        }catch (PDOException $e){
            die("Erreur : " . $e->getMessage());
        }
    }

    public static function getAllProducts() : array{
        try {
            $stmt = Database::getConn()->query("SELECT * FROM produit");
            $rawProduits = $stmt->fetchAll();
        } catch (PDOException $e) {
            die("Erreur : " . $e->getMessage());
        }
        $produits = [];
        foreach ($rawProduits as $rawProduit){
            $produits[] = new Produit($rawProduit['idProduit'], $rawProduit['libelleProduit'], $rawProduit['prixUnitaire'], enumTypeProduit::from($rawProduit['typeProduit']), $rawProduit['quantiteStock']);
        }
        return $produits;
    }

    public static function ajouterProduit($nom, $prix, $type) {
        try {
            Database::getConn()->query("INSERT INTO produit (libelleProduit, prixUnitaire, typeProduit) VALUES ('$nom', $prix, '$type')");
        }catch (PDOException $e){
            die("Erreur : " . $e->getMessage());
        }

    }

    public static function getProductById(int $id) : Produit{
        try{
            $stmt = Database::getConn()->prepare("SELECT * FROM produit WHERE idProduit = $id");
            $stmt->execute();
            $rawProduit = $stmt->fetch();

            return new Produit($rawProduit['idProduit'], $rawProduit['libelleProduit'], $rawProduit['prixUnitaire'],enumTypeProduit::from($rawProduit['typeProduit']), $rawProduit['quantiteStock']);

        }catch (PDOException $e){
            die("Erreur : " . $e->getMessage());
        }
    }



}
