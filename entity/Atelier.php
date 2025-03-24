<?php
class Atelier
{
    private $idAtelier;
    private $thematiqueAtelier;
    private enumTypeProduit $typeProduit;
    private $dateAtelier;
    private $prixAtelier;
    private StatutAtelier $statut;
    private $emailWoofer;


    public function __construct($idAtelier, $thematiqueAtelier, $typeProduit, $dateAtelier, $prixAtelier, $statutAtelier, $emailWoofer)
    {
        $this->idAtelier = $idAtelier;
        $this->thematiqueAtelier = $thematiqueAtelier;
        $this->typeProduit = $typeProduit;
        $this->dateAtelier = $dateAtelier;
        $this->prixAtelier = $prixAtelier;
        $this->statut = $statutAtelier;
        $this->emailWoofer = $emailWoofer;
    }


    public function inscrireParticipant($pdo): void
    {
        $email = $_POST['email'];
        $statement = $pdo->prepare("INSERT INTO participe (emailInscrit, idAtelier) VALUES (?, ?)");
        $statement->bindParam(1, $email, PDO::PARAM_STR);
        $statement->bindParam(2, $this->idAtelier, PDO::PARAM_INT);
        $statement->execute();
    }

    public static function annulerAtelier($pdo)
    {
        $idAtelier = $_POST['atelierId'];
        $statement = $pdo->prepare("UPDATE atelier SET statutAtelier = 'Annulé' WHERE idAtelier = ?");
        $statement->bindParam(1, $idAtelier, PDO::PARAM_INT);
        $statement->execute();
    }


    public function terminerAtelier($pdo)
    {
        $statement = $pdo->prepare("UPDATE atelier SET statutAtelier = 'Terminé' WHERE idAtelier = ?");
        $statement->bindParam(1, $this->idAtelier, PDO::PARAM_INT);
        $statement->execute();
    }
    public function CommencerAtelier($pdo)
    {
        $statement = $pdo->prepare("UPDATE atelier SET statutAtelier = 'En_cours' WHERE idAtelier = ?");
        $statement->bindParam(1, $this->idAtelier, PDO::PARAM_INT);
        $statement->execute();
    }

    public function modifierDateAtelier($pdo, DateTime $dateAtelier)
    {
        $dateAtelier = $dateAtelier->format('Y-m-d');
        $statement = $pdo->prepare("UPDATE atelier SET dateAtelier = '$dateAtelier' WHERE idAtelier = ?");
        $statement->bindParam(1, $this->idAtelier, PDO::PARAM_INT);
        $statement->execute();
    }

    public function creerAtelier($pdo)
    {
        $typeProduit = $this->typeProduit->value;
        $statut = $this->statut->value;
        $statement = $pdo->prepare("INSERT INTO atelier (thematiqueAtelier, typeProduitAtelier, dateAtelier, prixAtelier, statutAtelier, emailWoofer) VALUES (?, ?, ?, ?, ?, ?)");
        $statement->bindParam(1, $this->thematiqueAtelier, PDO::PARAM_STR);
        $statement->bindParam(2, $typeProduit, PDO::PARAM_STR);
        $statement->bindParam(3, $this->dateAtelier, PDO::PARAM_STR);
        $statement->bindParam(4, $this->prixAtelier, PDO::PARAM_STR);
        $statement->bindParam(5, $statut, PDO::PARAM_STR);
        $statement->bindParam(6, $this->emailWoofer, PDO::PARAM_STR);
        $statement->execute();
    }

    public static function getAteliersByEmail($email){
        try{
            $stmt = Database::getConn()->prepare("SELECT * FROM atelier WHERE emailWoofer = '$email'");
            $stmt->execute();
            $ateliers = $stmt->fetchAll();
            return $ateliers;
        }catch (PDOException $e){
            die("Erreur : " . $e->getMessage());
        }
    }
}
