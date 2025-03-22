<?php
// Démarrage de la session
session_start();
require '../databases/database.php'; // Inclusion de la connexion à la BDD

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

// Récupération des ateliers depuis la BDD
try {
    $stmt = $pdo->query("SELECT * FROM atelier");
    $ateliers = $stmt->fetchAll();
    var_dump($ateliers);
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Ateliers - Eco-Ferme</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f3f3f3;
        }

        .navbar {
            background-color: #4CAF50;
        }

        .navbar-brand,
        .nav-link {
            color: white;
        }

        .table thead {
            background-color: #4CAF50;
            color: white;
        }

        .btn-green {
            background-color: #4CAF50;
            color: white;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">🌿ECO-FERME</a>
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="http://localhost/CSIAPP/view/GestProduit.php">Produit</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Ventes</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Stocks</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Atelier</a></li>
                <li class="nav-item"><a class="nav-link" href="http://localhost/CSIAPP/view/GestWoofer.php">Woofer</a></li>
                <li class="nav-item"><a class="nav-link" href="http://localhost/CSIAPP/view/Profil.php">Profil</a></li>
                <li class="nav-item"><a class="nav-link" href="http://localhost/CSIAPP/databases/logout.php">Se déconnecter</a></li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <h2 class="mb-3">Gestion des Ateliers</h2>
        <input type="text" class="form-control mb-3" placeholder="Rechercher un atelier...">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Date</th>
                    <th>Responsable</th>
                    <th>Participants</th>
                    <th>Gérer Session</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ateliers as $atelier): ?>
                    <tr>
                        <td><?= htmlspecialchars($atelier['thematiqueAtelier']) ?></td>
                        <td><?= htmlspecialchars($atelier['dateAtelier']) ?></td>
                        <td><?= htmlspecialchars($atelier['emailWoofer']) ?></td>
                        <td><button class="btn btn-success btn-sm">Voir</button></td>
                        <td><button class="btn btn-green btn-sm">Gérer</button></td>
                        <td>
                            <button class="btn btn-primary btn-sm">Modifier</button>
                            <button class="btn btn-danger btn-sm">Annuler</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <button class="btn btn-green">Ajouter un Atelier</button>
    </div>
</body>

</html>