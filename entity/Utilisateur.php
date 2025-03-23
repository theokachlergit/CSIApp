<?php

class Utilisateur
{
    private string $email;
    private string $motDePasse;
    private Role $role;


    public function __construct(string $email, string $motDePasse, Role $role)
    {
        $this->email = $email;
        $this->motDePasse = $motDePasse;
        $this->role = $role;
    }

    public function authentifier(): bool
    {;

        require '../databases/database.php';
        $pdo = Database::getConn(); // Inclusion de la connexion à la base de données
        try {
            $statement = $pdo->prepare("SELECT email, mdpUtilisateur, roleUtilisateur FROM utilisateur WHERE email = ?");
            $statement->execute([$this->email]);
            $user = $statement->fetch();
            if ($user && password_verify($this->motDePasse, $user['mdpUtilisateur'])) {
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['roleUtilisateur'];
                try {
                    $statement = $pdo->prepare("SELECT * FROM woofer WHERE adresseWoofer = ?");
                    $statement->execute([$this->email]);
                    $statement->bindParam(1, $this->email);
                    $statement->execute();
                    $woofer = $statement->fetch();

                    if ($woofer) {
                        $_SESSION['email'] = $woofer['adresseWoofer'];
                        header("Location: GestWoofer.php");
                    }
                    return true;
                } catch (PDOException $e) {
                    return false;
                }
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return false;
        }
        return false;
    }

    public function modifierProfil(PDO $pdo): void
    {
        try {
            $motDePasseChiffre = password_hash($this->motDePasse, PASSWORD_DEFAULT);
            $statement = $pdo->prepare("UPDATE utilisateur SET mdpUtilisateur = ? WHERE email = ?");
            $statement->execute([$motDePasseChiffre, $this->email]);
        } catch (PDOException $e) {
        }
    }

    public function addUtilisateur(PDO $pdo): void
    {
        try {
            $motDePasseChiffre = password_hash($this->motDePasse, PASSWORD_DEFAULT);
            $statement = $pdo->prepare("INSERT INTO utilisateur (email, mdpUtilisateur, roleUtilisateur) VALUES (?, ?, ?)");
            $statement->execute([$this->email, $motDePasseChiffre, $this->role->name]);
        } catch (PDOException $e) {
        }
    }
}
