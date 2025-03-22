<?php

class Utilisateur
{
    private string $email;
    private string $motDePasse;
    private string $role;

    public static function authentifier(): bool
    {
        require '../databases/database.php'; // Inclusion de la connexion à la base de données
        $email = $_POST['email'];
        $password = $_POST['password'];
        try {
            $statement = $pdo->prepare("SELECT email, mdpUtilisateur, roleUtilisateur FROM utilisateur WHERE email = ?");
            $statement->execute([$email]);
            $user = $statement->fetch();
            //il faudra supprimer password_hash quand la page de création d'utilisateur sera faites.
            // if ($user && $password == $user['mdpUtilisateur']) {
                if ($user && password_verify($password, $user['mdpUtilisateur'])) {
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['roleUtilisateur'];
                try {
                    $statement = $pdo->prepare("SELECT * FROM woofer WHERE adresseWoofer = ?");
                    $statement->execute([$email]);
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

    public function modifierProfil($motDePasse): void
    {
        require '../databases/database.php'; // Inclusion de la connexion à la base de données
        $email = $_SESSION['email'];
        try {
            $motDePasse = password_hash($motDePasse, PASSWORD_DEFAULT);
            $statement = $pdo->prepare("UPDATE utilisateur SET mdpUtilisateur = ? WHERE email = ?");
            $statement->execute([$motDePasse, $email]);
        } catch (PDOException $e) {
        }
    }
}
