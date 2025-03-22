<?php
session_start();
$connect = false;
$firstArrive = true;
if (isset($_POST['login'])) {
    $firstArrive = false;
    require '../controller/AppController.php';
    $connect = auth();
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
                <?php if (!$connect && !$firstArrive) echo "<div class='alert alert-danger mt-3'>Erreur de connexion ou email/mot de passe incorrect</div>"; ?>
                <?php if ($connect) {
                    echo "<div class='alert alert-success mt-3'>Connexion reussie</div>";
                    header("Location: gestAtelier.php");
                }
                ?>
            </div>
        </div>
    </div>
</body>

</html>