<?php
session_start();
require '../databases/database.php';
$email = $_SESSION['email'];
// Récupération des profils$profils depuis la base de données
$query = "SELECT * FROM Utilisateur INNER JOIN Woofer ON Woofer.emailPersonneUtilisateur = '" . $email . "' INNER JOIN Personne ON personne.email = '" . $email  . "' WHERE Utilisateur.email = '" . $email . "'";
$stmt = $pdo->query($query);
$profils = $stmt->fetchAll(PDO::FETCH_ASSOC);
if (isset($_POST['modify'])) {
    require '../controller/AppController.php';
    modifyProfil();
}
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
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand" href="#">🌿ECO-FERME</a>
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="http://localhost/CSIAPP/view/GestProduit.php">Produit</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Ventes</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Stocks</a></li>
                    <li class="nav-item"><a class="nav-link" href="http://localhost/CSIAPP/view/GestAtelier.php">Atelier</a></li>
                    <li class="nav-item"><a class="nav-link" href="http://localhost/CSIAPP/view/GestWoofer.php">Woofer</a></li>
                    <li class="nav-item"><a class="nav-link" href="http://localhost/CSIAPP/view/Profil.php">Profil</a></li>
                    <li class="nav-item"><a class="nav-link" href="http://localhost/CSIAPP/databases/logout.php">Se déconnecter</a></li>
                </ul>
            </div>
        </nav>


        <h2 class="mt-4">Gestion de votre profils</h2>
        <table class="table table-bordered">
            <thead class="table-success">
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Num Tel</th>
                    <th>Date Début</th>
                    <th>Date Fin</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($profils as $profil): ?>
                    <tr>
                        <td><?= htmlspecialchars($profil['nom']) ?></td>
                        <td><?= htmlspecialchars($profil['prenom']) ?></td>
                        <td><?= htmlspecialchars($profil['email']) ?></td>
                        <td><?= htmlspecialchars($profil['numTel']) ?></td>
                        <td><?= htmlspecialchars($profil['dateDebSejour']) ?></td>
                        <td><?= htmlspecialchars($profil['dateFinSejour']) ?></td>
                        <td>
                            <a class="btn btn-green btn-sm" data-bs-toggle="modal" data-bs-target="#editWooferModal">Modifier</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal de modification -->
    <div class="modal fade" id="editWooferModal" tabindex="-1" aria-labelledby="editWooferLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editWooferLabel">Modifier un Woofer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post">
                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="nom" name="nom" required value="<?= $profil['nom'] ?>">
                        </div>
                        <div class="mb-3">
                            <label for="prenom" class="form-label">Prénom</label>
                            <input type="text" class="form-control" id="prenom" name="prenom" required value="<?= $profil['prenom'] ?>">
                        </div>
                        <div class="mb-3">
                            <label for="numTel" class="form-label">Numéro de téléphone</label>
                            <input type="tel" class="form-control" id="numTel" name="numTel" required value="<?= $profil['numTel'] ?>">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" disabled=true class="form-control" id="email" name="email" required value="<?= $profil['email'] ?>">
                        </div>
                        <!-- Champ pour le mot de passe (mdpUtilisateur) -->
                        <div class="mb-3">
                            <label for="mdpUtilisateur" class="form-label">Mot de passe</label>
                            <input type="password" class="form-control" id="mdpUtilisateur" name="mdpUtilisateur" required>
                        </div>
                        <!-- Champ pour l'adresse (adresseWoofer) -->
                        <div class="mb-3">
                            <label for="adresseWoofer" class="form-label">Adresse</label>
                            <input type="text" class="form-control" id="adresseWoofer" name="adresseWoofer" required value="<?= $profil['adresseWoofer'] ?>">
                        </div>
                        <button type="submit" name="modify" class="btn btn-success">Modifier</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>