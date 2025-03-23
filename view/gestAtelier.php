<?php
// Démarrage de la session
session_start();
require '../databases/database.php';
require '../controller/AppController.php';
require '../enum/enumTypeProduit.php';
require '../enum/statutAtelier.php';
$pdo = Database::getConn(); // Inclusion de la connexion à la BDD

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['email'])) {
    header("Location: loginPage.php");
    exit();
}
if (isset($_POST['create'])) {
    addAtelier($pdo);
}
if (isset($_POST['Inscrire'])) {
    inscrire($pdo);
}

// Récupération des ateliers depuis la BDD
$ateliers = getAllAteliers($pdo);
$personnes = getAllPersonne($pdo);
var_dump($personnes)
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Ateliers - Eco-Ferme</title>
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
        <h2 class="mb-3">Gestion des Ateliers</h2>
        <table class="table table-bordered">
            <thead class="table-success">
                <tr>
                    <th>Nom</th>
                    <th>Date</th>
                    <th>Responsable</th>
                    <?php
                    if (isset($_SESSION['role'])) {
                        if ($_SESSION['role'] == 'Responsable') {
                            echo '<th>Gérer Session</th> <th>Annulé</th>';
                        } else {
                            echo '<th>Voir</th>';
                        }
                    }
                    ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ateliers as $atelier): ?>
                    <tr>
                        <td><?= htmlspecialchars($atelier['thematiqueAtelier']) ?></td>
                        <td><?= htmlspecialchars($atelier['dateAtelier']) ?></td>
                        <td><?= htmlspecialchars($atelier['emailWoofer']) ?></td>
                        <?php if (isset($_SESSION['role'])) { ?>
                            <?php if ($_SESSION['role'] == 'Responsable') { ?>
                                <td><button class="btn btn-green btn-sm">Gérer</button>
                                    <button type="button" class="btn btn-sm btn-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#inscrirePersonneModal"
                                        data-atelierid="42">
                                        Inscrire
                                    </button>
                                </td>
                                <td><button class="btn btn-danger btn-sm">Annuler</button></td>
                            <?php } else { ?>
                                <td><button class="btn btn-success btn-sm">Voir</button></td>
                        <?php }
                        } ?>

                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <!-- Bouton pour ouvrir la modal -->
        <button class="btn btn-green w-100" data-bs-toggle="modal" data-bs-target="#addAtelierModal">
            Ajouter un Atelier
        </button>
        <!-- Modal de création d'atelier -->
        <div class="modal fade" id="addAtelierModal" tabindex="-1" aria-labelledby="addAtelierModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addAtelierModalLabel">Créer un nouvel atelier</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        <form method="post">
                            <div class="mb-3">
                                <label for="thematiqueAtelier" class="form-label">Thématique</label>
                                <input type="text" class="form-control" name="thematiqueAtelier" id="thematiqueAtelier" placeholder="Ex : Jardinage" required>
                            </div>
                            <div class="mb-3">
                                <label for="typeProduit" class="form-label">Type de produit</label>
                                <select class="form-select" name="typeProduit" id="typeProduit" required>
                                    <?php foreach (EnumTypeProduit::cases() as $type): ?>
                                        <option value="<?= $type->value ?>"><?= $type->value ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="dateAtelier" class="form-label">Date de l'atelier</label>
                                <input type="date" class="form-control" name="dateAtelier" id="dateAtelier" required>
                            </div>
                            <div class="mb-3">
                                <label for="prixAtelier" class="form-label">Prix</label>
                                <input type="number" class="form-control" name="prixAtelier" id="prixAtelier" placeholder="Ex : 50.00" step="0.01" required>
                            </div>
                            <div class="mb-3">
                                <label for="statut" class="form-label">Statut</label>
                                <select class="form-select" name="statut" id="statut" required>
                                    <?php foreach (StatutAtelier::cases() as $statut): ?>
                                        <option value="<?= $statut->value ?>"><?= $statut->value ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="emailWoofer" class="form-label">Email du woofer</label>
                                <select class="form-select" name="email" id="email" required>
                                    <?php foreach (getAllWoofers($pdo) as $woofer): ?>
                                        <option value="<?= $woofer['emailPersonneUtilisateur'] ?>"><?= $woofer['emailPersonneUtilisateur'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                <button type="submit" name="create" class="btn btn-green">Créer l'atelier</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="inscrirePersonneModal" tabindex="-1" aria-labelledby="inscrirePersonneModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="inscrirePersonneModalLabel">Inscrire une personne</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <form method="post">
                        <!-- Liste déroulante pour sélectionner un email parmi les personnes -->
                        <div class="mb-3">
                            <label for="personneEmail" class="form-label">Sélectionnez un email</label>
                            <select class="form-select" name="email" id="personneEmail" required>
                                <?php foreach ($personnes as $p): ?>
                                    <option value="<?= htmlspecialchars($p['email']) ?>"><?= htmlspecialchars($p['email']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <!-- Champ caché pour l'id de l'atelier -->
                        <input type="hidden" name="atelierId" id="atelierId" value="">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-primary" name="Inscrire">Inscrire</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Code JavaScript pour mettre à jour le champ caché avec l'id de l'atelier -->
    <script>
        var inscrireModal = document.getElementById('inscrirePersonneModal');
        inscrireModal.addEventListener('show.bs.modal', function(event) {
            // Récupère le bouton qui a déclenché l'ouverture de la modal
            var button = event.relatedTarget;
            // Récupère l'id de l'atelier depuis l'attribut data-atelierid
            var atelierId = button.getAttribute('data-atelierid');
            // Met à jour le champ caché dans la modal
            var inputAtelierId = inscrireModal.querySelector('#atelierId');
            inputAtelierId.value = atelierId;
        });
    </script>

    <!-- Inclusion de Bootstrap CSS et JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>