<?php
session_start();
require 'database.php';

// Récupération des woofers depuis la base de données
$query = "SELECT * FROM woofers";
$stmt = $pdo->query($query);
$woofers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Woofers</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .header {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            text-align: center;
        }
        .btn-green {
            background-color: #4CAF50;
            color: white;
        }
        .btn-green:hover {
            background-color: #388E3C;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>🌿 ECO-FERME 🌿</h1>
    </div>

    <div class="container mt-4">
        <nav class="nav justify-content-center">
            <a class="nav-link" href="#">Accueil</a>
            <a class="nav-link" href="#">Ventes</a>
            <a class="nav-link" href="#">Stocks</a>
            <a class="nav-link active" href="#">Woofers</a>
            <a class="nav-link" href="#">Produits</a>
        </nav>

        <h2 class="mt-4">Gestion des Woofers</h2>

        <input type="text" class="form-control mb-3" placeholder="Rechercher un woofer...">

        <table class="table table-bordered">
            <thead class="table-success">
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Date Début</th>
                    <th>Date Fin</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($woofers as $woofer): ?>
                    <tr>
                        <td><?= htmlspecialchars($woofer['nom']) ?></td>
                        <td><?= htmlspecialchars($woofer['prenom']) ?></td>
                        <td><?= htmlspecialchars($woofer['email']) ?></td>
                        <td><?= htmlspecialchars($woofer['date_debut']) ?></td>
                        <td><?= htmlspecialchars($woofer['date_debut']) ?></td>
                        <td><?= htmlspecialchars($woofer['date_fin']) ?></td>
                        <td>
                            <a href="modifier_woofer.php?id=<?= $woofer['id'] ?>" class="btn btn-success btn-sm">Modifier</a>
                            <a href="supprimer_woofer.php?id=<?= $woofer['id'] ?>" class="btn btn-danger btn-sm">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <button class="btn btn-green w-100">Ajouter un Woofer</button>
    </div>

</body>
</html>
