<?php
session_start();
require '../databases/database.php'; // Inclusion de la connexion à la BDD

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

// Récupération des produits depuis la BDD
try {
    $stmt = $pdo->query("SELECT * FROM produit");
    $produits = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Produits - Eco-Ferme</title>
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
            <li class="nav-item"><a class="nav-link" href="#">Produit</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Ventes</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Stocks</a></li>
            <li class="nav-item"><a class="nav-link" href="http://localhost/CSIAPP/view/GestAtelier.php">Atelier</a></li>
            <li class="nav-item"><a class="nav-link" href="http://localhost/CSIAPP/view/GestWoofer.php">Woofer</a></li>
            <li class="nav-item"><a class="nav-link" href="http://localhost/CSIAPP/view/Profil.php">Profil</a></li>
            <li class="nav-item"><a class="nav-link" href="http://localhost/CSIAPP/databases/logout.php">Se déconnecter</a></li>
        </ul>
    </div>
</nav>

<div class="container mt-4">
    <h2 class="mb-3">Gestion des Produits</h2>
    <input type="text" class="form-control mb-3" placeholder="Rechercher un produit...">
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Nom</th>
            <th>Description</th>
            <th>Prix</th>
            <th>Quantité</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($produits as $produit): ?>
            <tr>
                <td><?= htmlspecialchars($produit['nomProduit']) ?></td>
                <td><?= htmlspecialchars($produit['descriptionProduit']) ?></td>
                <td><?= htmlspecialchars($produit['prixProduit']) ?></td>
                <td><?= htmlspecialchars($produit['quantiteProduit']) ?></td>
                <td>
                    <button class="btn btn-primary btn-sm">Modifier</button>
                    <button class="btn btn-danger btn-sm">Supprimer</button>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <button class="btn btn-green">Ajouter un Produit</button>
</div>
</body>

</html>
