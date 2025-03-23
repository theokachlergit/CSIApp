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
        $email = $_SESSION['email'];
        try {
            $statement = $pdo->prepare("UPDATE woofer SET adresseWoofer = ?, photoWoofer = ?, date_debut = ?, date_fin = ? WHERE email = ?");
            $statement->bindParam(1, $this->adresse, PDO::PARAM_STR);
            $statement->bindParam(2, $this->photo, PDO::PARAM_STR);
            $statement->bindParam(3, $this->date_debut, PDO::FB_ATTR_DATE_FORMAT);
            $statement->bindParam(4, $this->date_fin, PDO::FB_ATTR_DATE_FORMAT);
            $statement->bindParam(5, $email, PDO::PARAM_STR);
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
}
