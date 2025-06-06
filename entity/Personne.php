<?php
class Personne
{
    private string $nom;
    private string $prenom;
    private string $telephone;
    private string $email;
    public function __construct(string $email, string $nom, string $prenom, string $telephone)
    {
        $this->email = $email;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->telephone = $telephone;
    }

    public function getNom(): string
    {
        return $this->nom;
    }
    public function getPrenom(): string
    {
        return $this->prenom;
    }
    public function getTelephone(): string
    {
        return $this->telephone;
    }

    public function modifierProfil($pdo)
    {
        try {
            $statement = $pdo->prepare("UPDATE personne SET nom = ?, prenom = ?, numTel = ? WHERE email = ?");
            $statement->bindParam(1, $this->nom, PDO::PARAM_STR);
            $statement->bindParam(2, $this->prenom, PDO::PARAM_STR);
            $statement->bindParam(3, $this->telephone, PDO::PARAM_STR);
            $statement->bindParam(4, $this->email, PDO::PARAM_STR);
            $statement->execute();
        } catch (PDOException $e) {
            var_dump($e);
        }
    }

    public function deletePersonne($pdo)
    {
        try {
            $statement = $pdo->prepare("DELETE FROM personne WHERE email = ?");
            $statement->bindParam(1, $this->email, PDO::PARAM_STR);
            $statement->execute();
        } catch (PDOException $e) {
            var_dump($e);
        }
    }

    public function addPersonne($pdo)
    {
        try {
        $statement = $pdo->prepare("INSERT INTO personne (email, nom, prenom, numTel) VALUES (?, ?, ?, ?)");
        $statement->bindParam(1, $this->email, PDO::PARAM_STR);
        $statement->bindParam(2, $this->nom, PDO::PARAM_STR);
        $statement->bindParam(3, $this->prenom, PDO::PARAM_STR);
        $statement->bindParam(4, $this->telephone, PDO::PARAM_STR);
        $statement->execute();
        } catch (PDOException $e) {
            var_dump($e);
        }
    }
}
