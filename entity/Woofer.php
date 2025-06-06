<?php
require 'Personne.php';
class Woofer extends Personne
{
    private String $adresse;
    private String $photo;
    public DateTime $date_debut;
    public DateTime $date_fin;

    public function __construct($adresse, $photo, $date_debut, $date_fin, $email, $nom, $prenom, $numTel)
    {
        parent::__construct($email, $nom, $prenom, $numTel);
        $this->adresse = $adresse;
        $this->photo = $photo;
        $this->date_debut = $date_debut;
        $this->date_fin = $date_fin;
    }

    public function prolongerSejour($pdo, int $duree)
    {
        $email = $_POST['email'];
        try {
            if ($duree < 0) {
                $duree = $duree * -1;
                var_dump($duree);
                $dateFin = $this->date_fin->sub(new DateInterval('P' . $duree . 'D'))->format('Y-m-d');
            } else {
                var_dump($duree);
                $dateFin = $this->date_fin->add(new DateInterval('P' . $duree . 'D'))->format('Y-m-d');
            }
            $statement = $pdo->prepare("UPDATE woofer SET  dateFinSejour = ? WHERE emailPersonneUtilisateur = ?");
            $statement->bindParam(1, $dateFin, PDO::PARAM_STR);
            $statement->bindParam(2, $email, PDO::PARAM_STR);
            $statement->execute();
        } catch (PDOException $e) {
            var_dump($e);
        } catch (Exception $e) {
            // var_dump($e);
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

    public function creerWoofer($pdo)
    {
        $email = $_POST['email'];
        var_dump($email);
        $date_debut = $this->date_debut->format('Y-m-d');
        $dateFin = $this->date_fin->format('Y-m-d');
        $statement = $pdo->prepare("INSERT INTO woofer (emailPersonneUtilisateur, adresseWoofer, photoWoofer, dateDebSejour, dateFinSejour) VALUES (?, ?, ?, ?, ?)");
        $statement->bindParam(1, $email, PDO::PARAM_STR);
        $statement->bindParam(2, $this->adresse, PDO::PARAM_STR);
        $statement->bindParam(3, $this->photo, PDO::PARAM_STR);
        $statement->bindParam(4, $date_debut, PDO::PARAM_STR);
        $statement->bindParam(5, $dateFin, PDO::PARAM_STR);
        $statement->execute();
    }
}
