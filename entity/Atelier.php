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


    public function inscrireParticipant($pdo, $email): void {
        $statement = $pdo->prepare("INSERT INTO inscrit (emailPersonne) VALUES ?)");
        $statement->bindParam(1, $email, PDO::PARAM_STR);
        $statement->execute();
        $statement = $pdo->prepare("INSERT INTO participe (emailInscrit, idAtelier) VALUES (?, ?)");
        $statement->bindParam(1, $email, PDO::PARAM_STR);
        $statement->bindParam(2, $this->idAtelier, PDO::PARAM_INT);
        $statement->execute();
    }

    public function annulerAtelier($pdo)
    {
        $pdo->query("UPDATE atelier SET statutAtelier = 'Annulé' WHERE idAtelier = $this->idAtelier");
        $pdo->execute();
    }

    public function terminerAtelier($pdo)
    {
        $pdo->query("UPDATE atelier SET statutAtelier = 'Terminé' WHERE idAtelier = $this->idAtelier");
        $pdo->execute();
    }
    public function CommencerAtelier($pdo)
    {
        $pdo->query("UPDATE atelier SET statutAtelier = 'En_cours' WHERE idAtelier = $this->idAtelier");
        $pdo->execute();
    }




    public function modifierDateAtelier($pdo, DateTime $dateAtelier)
    {
        $pdo->query("UPDATE atelier SET dateAtelier = '$dateAtelier' WHERE idAtelier = $this->idAtelier");
        $pdo->execute();
    }

    public function creerAtelier($pdo)
    {
        $typeProduit = $this->typeProduit->value;
        var_dump($typeProduit);
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
}
