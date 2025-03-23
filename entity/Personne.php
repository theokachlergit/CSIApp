<?php
class Personne
{
    protected string $nom;
    protected string $prenom;
    protected string $email;
    protected string $telephone;

    public function __construct(int $id, string $nom, string $prenom)
    {
        $this->nom = $nom;
        $this->prenom = $prenom;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    public function getPrenom(): string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): void
    {
        $this->prenom = $prenom;
    }

    public function inscrire() {}

    public function modifierInformations() {
        require '../databases/database.php'; // Inclusion de la connexion à la base de données
        $this->$email = $_SESSION['email'];
        
    }
}
