<?php
session_start();
require '../databases/database.php';
$pdo = Database::getConn();
$email = $_SESSION['email'];
// Récupération des profils$profils depuis la base de données
$query = "SELECT * FROM Utilisateur WHERE Utilisateur.email = '" . $email . "'";
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
    <link rel="stylesheet" href="css.css">
</head>

<body>

    <div class="header">
        <h1>🌿 ECO-FERME 🌿</h1>
    </div>

    <div class="container mt-4">
        <?php
        if (isset($_SESSION['role'])) {
            if ($_SESSION['role'] != 'Administrateur') {
                require 'nav-bar.html';
            } else {
                require 'nav-bar-admin.html';
            }
        }
        ?>

        <h2 class="mt-4">Gestion de votre profils</h2>
        <table class="table table-bordered">
            <thead class="table-success">
                <tr>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($profils as $profil): ?>
                    <tr>
                        <td><?= htmlspecialchars($profil['email']) ?></td>
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
                        <!-- Champ pour le mot de passe (mdpUtilisateur) -->
                        <div class="mb-3">
                            <label for="mdpUtilisateur" class="form-label">Mot de passe</label>
                            <input type="password" class="form-control" id="mdpUtilisateur" name="mdpUtilisateur" required>
                        </div>
                        <!-- Champ pour l'adresse (adresseWoofer) -->
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>