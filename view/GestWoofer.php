<?php
session_start();
require '../databases/database.php';
$pdo = Database::getConn();

// Récupération des woofers depuis la base de données
$query = "SELECT * FROM woofer INNER JOIN personne ON woofer.emailPersonneUtilisateur = personne.email";
$stmt = $pdo->query($query);
$woofers = $stmt->fetchAll(PDO::FETCH_ASSOC);
if (isset($_POST['modify'])) {
    require '../controller/AppController.php';
    modifyWoofer($pdo);
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
    <?php
    if (isset($_SESSION['role'])) {
        if ($_SESSION['role'] != 'Responsable') {
            require 'nav-bar.html';
        } else {
            require 'nav-bar-admin.html';
        }
    }
    ?>
    <div class="container-xxl">


        <h2 class="mt-4">Gestion des Woofers</h2>

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
                            <a class="btn btn-green btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#editWooferModal"
                                data-email="<?= htmlspecialchars($woofer['email']) ?>"
                                data-deb="<?= htmlspecialchars($woofer['dateDebSejour']) ?>"
                                data-fin="<?= htmlspecialchars($woofer['dateFinSejour']) ?>">
                                Modifier
                            </a>
                            <a class="btn btn-danger btn-sm">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <button class="btn btn-green w-100" data-bs-toggle="modal" data-bs-target="#addWooferModal">Ajouter un Woofer</button>
    </div>
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
                            <label for="email" class="form-label">Email</label>
                            <input type="text" class="form-control" id="email2" name="email2" value="valeur affichée" disabled>
                            <input type="hidden" id="email" name="email" value="valeur affichée">
                        </div>
                        <div class="mb-3">
                            <label for="dateDebSejour" class="form-label">Date début</label>
                            <input type="date" class="form-control" name="dateDebSejour" id="dateDebSejour">
                        </div>
                        <div class="mb-3">
                            <label for="dateFinSejour" class="form-label">Date fin</label>
                            <input type="date" class="form-control" name="dateFinSejour" id="dateFinSejour">
                        </div>
                        <button type="submit" name="modify" class="btn btn-success">Modifier</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

<script>
    var editWooferModal = document.getElementById('editWooferModal');
    editWooferModal.addEventListener('show.bs.modal', function(event) {
        // Le bouton qui a déclenché l'ouverture de la modal
        var button = event.relatedTarget;
        var email = button.getAttribute('data-email');
        var dateDeb = button.getAttribute('data-deb');
        var dateFin = button.getAttribute('data-fin');

        // Remplir les champs du formulaire dans la modal
        var modal = this;
        modal.querySelector('input[name="email"]').value = email;
        modal.querySelector('input[name="email2"]').value = email;
        modal.querySelector('input[name="dateDebSejour"]').value = dateDeb;
        modal.querySelector('input[name="dateFinSejour"]').value = dateFin;
    });
</script>

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
                    <div>
                        <label for="numTel" class="form-label">Numéro de téléphone</label>
                        <input type="tel" class="form-control" id="numTel" name="numTel" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div>
                        <label for="mdpUtilisateur" class="form-label">Mot de passe</label>
                        <input type="password" class="form-control" id="mdpUtilisateur" name="mdpUtilisateur" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Adresse</label>
                        <input type="email" class="form-control" id="adresse" name="adresse" required>
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