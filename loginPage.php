<?php
session_start();
require 'databases/database.php'; // Inclusion de la connexion à la base de données

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        $statement = $pdo->prepare("SELECT email, mdpUtilisateur, roleUtilisateur FROM utilisateur WHERE email = ?");
        $statement->execute([$email]);
        $user = $statement->fetch();
        //il faudra supprimer password_hash quand la page de création d'utilisateur sera faites.
        if ($user && password_verify($password, password_hash($user['mdpUtilisateur'], PASSWORD_DEFAULT))) {
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['roleUtilisateur'];

            $success = "Connexion réussie.";
        } else {
            $error = "Nom d'utilisateur ou mot de passe incorrect.";
        }
    } catch (PDOException $e) {
        $error = "Erreur lors de la connexion: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'accueil</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h2 class="text-center">Bienvenue</h2>
        <div class="row justify-content-center">
            <div class="col-md-4">
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Nom d'utilisateur</label>
                        <input type="text" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mot de passe</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" name="login" class="btn btn-primary w-100">Connexion</button>
                </form>
                <?php if (isset($error)) echo "<div class='alert alert-danger mt-3'>$error</div>"; ?>
                <?php if (isset($success)) {
                    echo "<div class='alert alert-success mt-3'>$success</div>";
                    $success = false;
                    header("Location: view/gestAtelier.php");
                }
                ?>
            </div>
        </div>
    </div>
</body>

</html>