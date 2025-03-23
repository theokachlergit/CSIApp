<?php
session_start();
require '../databases/database.php';
$pdo = Database::getConn(); // Inclusion de la connexion à la BDD

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['email'])) {
    header("Location: loginPage.php");
    exit();
}

if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] != 'Responsable') {
        header("Location: ../view/GestProduit.php");
    }
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
    <link rel="stylesheet" href="css.css">

</head>

<body>
    <?php
    if (isset($_SESSION['role'])) {
        if ($_SESSION['role'] != 'Responsable') {
            require 'nav-bar.html';
        } else {
            require 'nav-bar-admin.html';
        }
    }
    ?>

    <div class="container-xxl
">
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