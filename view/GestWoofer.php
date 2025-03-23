<?php
session_start();
require '../databases/database.php';
$pdo = Database::getConn();

// Récupération des woofers depuis la base de données
$query = "SELECT * FROM woofer INNER JOIN personne ON woofer.emailPersonneUtilisateur = personne.email";
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
    <link rel="stylesheet" href="css.css">

</head>

<body>

    <?php
    if (isset($_SESSION['role'])) {
        if ($_SESSION['role'] != 'Administrateur') {
            require 'nav-bar.html';
        } else {
            require 'nav-bar-admin.html';
        }
    }
    ?>


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
                    <td><?= htmlspecialchars($woofer['dateDebSejour']) ?></td>
                    <td><?= htmlspecialchars($woofer['dateFinSejour']) ?></td>
                    <td>
                        <a class="btn btn-success btn-sm">Modifier</a>
                        <a class="btn btn-danger btn-sm">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <button class="btn btn-green w-100" data-bs-toggle="modal" data-bs-target="#addWooferModal">Ajouter un Woofer</button>
    </div>

    <!-- Modal d'ajout -->
    <div class="modal fade" id="addWooferModal" tabindex="-1" aria-labelledby="addWooferLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addWooferLabel">Ajouter un Woofer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="ajouter_woofer.php">
                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="nom" name="nom" required>
                        </div>
                        <div class="mb-3">
                            <label for="prenom" class="form-label">Prénom</label>
                            <input type="text" class="form-control" id="prenom" name="prenom" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="dateDebut" class="form-label">Date Début</label>
                            <input type="date" class="form-control" id="dateDebut" name="dateDebut" required>
                        </div>
                        <div class="mb-3">
                            <label for="dateFin" class="form-label">Date Fin</label>
                            <input type="date" class="form-control" id="dateFin" name="dateFin" required>
                        </div>
                        <button type="submit" class="btn btn-success">Ajouter</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>