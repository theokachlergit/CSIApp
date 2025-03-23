<?php
require_once 'Personne.php';
class Woofer extends Personne
{
    private String $adresse;
    private String $photo;
    public DateTime $date_debut;
    public DateTime $date_fin;

    public function __construct($adresse, $photo, $date_debut, $date_fin)
    {
        $this->adresse = $adresse;
        $this->photo = $photo;
        $this->date_debut = $date_debut;
        $this->date_fin = $date_fin;
    }

    public function modifierInformations($pdo)
    {
        $email = $_POST['email'];
        try {
            $dateDeb = $this->date_debut->format('Y-m-d');
            $dateFin = $this->date_fin->format('Y-m-d');
            $statement = $pdo->prepare("UPDATE woofer SET date_debut = ?, date_fin = ? WHERE email = ?");
            $statement->bindParam(1, $dateDeb, PDO::PARAM_STR);
            $statement->bindParam(2, $dateFin, PDO::PARAM_STR);
            $statement->bindParam(3, $email, PDO::PARAM_STR);
            $statement->execute();
        } catch (PDOException $e) {
        }
    }
    public function modifierProfil($pdo)
    {
        $email = $_SESSION['email'];
        try {
            $statement = $pdo->prepare("UPDATE woofer SET adresseWoofer = ?, photoWoofer = ? WHERE emailPersonneUtilisateur = ?");
            $statement->bindParam(1, $this->adresse, PDO::PARAM_STR);
            $statement->bindParam(2, $this->photo, PDO::PARAM_STR);
            $statement->bindParam(3, $email, PDO::PARAM_STR);
            $statement->execute();
        } catch (PDOException $e) {
        }
    }

    public function addWoofer($pdo)
    {
        $email = $_POST['email'];
        try {
            $statement = $pdo->prepare("INSERT INTO woofer (emailPersonneUtilisateur, adresseWoofer, photoWoofer, date_debut, date_fin) VALUES (?, ?, ?, ?, ?)");
            $statement->bindParam(1, $email, PDO::PARAM_STR);
            $statement->bindParam(2, $this->adresse, PDO::PARAM_STR);
            $statement->bindParam(3, $this->photo, PDO::PARAM_STR);
            $statement->bindParam(4, $this->date_debut, PDO::PARAM_STR);
            $statement->bindParam(5, $this->date_fin, PDO::PARAM_STR);
            $statement->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function deleteWoofer($pdo)
    {
        $email = $_POST['email'];
        try {
            $statement = $pdo->prepare("DELETE FROM woofer WHERE emailPersonneUtilisateur = ?");
            $statement->bindParam(1, $email, PDO::PARAM_STR);
            $statement->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
