<?php
session_start();
require '../databases/database.php';
$pdo = Database::getConn();
require '../controller/AppController.php';
// Vérifier qu'un email est passé en GET
if (!isset($_GET['email'])) {
    echo "Aucun woofer spécifié.";
    exit;
}

$wooferEmail = $_GET['email'];

// (Optionnel) Vérifier que ce woofer existe
$checkWoofer = $pdo->prepare("SELECT emailPersonneUtilisateur FROM woofer WHERE emailPersonneUtilisateur = ?");
$checkWoofer->execute([$wooferEmail]);
if (!$checkWoofer->fetch()) {
    echo "Ce woofer n'existe pas dans la base de données.";
    exit;
}

// Gérer les actions (ajout, suppression, création) en POST
if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'addFormationExistante':
            addFormationExistante($pdo, $wooferEmail);
            break;

        case 'removeFormation':
            removeFormation($pdo, $wooferEmail);
            break;

        case 'createFormation':
            createFormation($pdo, $wooferEmail);
    }
    // Redirection pour éviter la double soumission du formulaire
    header("Location: ../view/gestFormation.php?email=" . $wooferEmail);
    exit;
}

// Récupérer la liste des formations déjà suivies
$stmt = $pdo->prepare("
    SELECT f.idFormation, f.libelleFormation
    FROM formation f
    JOIN suit s ON f.idFormation = s.idFormation
    WHERE s.emailWoofer = ?
");
$stmt->execute([$wooferEmail]);
$formationsWoofer = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer la liste de toutes les formations
$stmtAll = $pdo->query("SELECT idFormation, libelleFormation FROM formation");
$toutesFormations = $stmtAll->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Gestion des formations du woofer</title>
    <!-- CSS de Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Votre fichier de style personnalisé -->
    <link rel="stylesheet" href="css.css">
</head>

<body>
    <?php if (isset($_SESSION['role'])) {
        if ($_SESSION['role'] != 'Responsable') {
            header("Location: ../view/profil.php");
            require 'nav-bar.html'; // Note : header() + require() ici n’est pas forcément cohérent,
            // car header() redirige avant d'inclure. Vérifiez cette logique.
        } else {
            require 'nav-bar-admin.html';
        }
    } ?>

    <div class="container-xxl">
        <h2>Formations du Woofer (<?= htmlspecialchars($wooferEmail) ?>)</h2>
        <?php if (count($formationsWoofer) > 0): ?>
            <table class="table table-bordered">
                <thead class="table-success">
                    <tr>
                        <th>ID</th>
                        <th>Libellé</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($formationsWoofer as $formation): ?>
                        <tr>
                            <td><?= htmlspecialchars($formation['idFormation']) ?></td>
                            <td><?= htmlspecialchars($formation['libelleFormation']) ?></td>
                            <td>
                                <!-- Formulaire de suppression -->
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="idFormation" value="<?= $formation['idFormation'] ?>">
                                    <input type="hidden" name="action" value="removeFormation">
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        Retirer
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Aucune formation suivie pour ce woofer.</p>
        <?php endif; ?>

        <hr>

        <!-- Ajouter une formation existante -->
        <h2>Ajouter une formation existante</h2>
        <form method="post" class="mb-4">
            <input type="hidden" name="action" value="addFormationExistante">
            <div class="mb-3">
                <label for="idFormation" class="form-label">Sélectionnez une formation :</label>
                <select name="idFormation" id="idFormation" class="form-select" required>
                    <option value=""> Choisir </option>
                    <?php foreach ($toutesFormations as $formation): ?>
                        <option value="<?= $formation['idFormation'] ?>">
                            <?= htmlspecialchars($formation['libelleFormation']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Ajouter</button>
        </form>

        <hr>

        <!-- Créer une nouvelle formation -->
        <h2>Créer une nouvelle formation</h2>
        <form method="post">
            <input type="hidden" name="action" value="createFormation">
            <div class="mb-3">
                <label for="libelleFormation" class="form-label">Libellé de la formation :</label>
                <input type="text" name="libelleFormation" id="libelleFormation" class="form-control" required>
            </div>
            <!-- Case à cocher pour ajouter directement la formation au woofer -->
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="addToWoofer" id="addToWoofer" value="1">
                <label class="form-check-label" for="addToWoofer">
                    Ajouter immédiatement cette formation à ce woofer
                </label>
            </div>
            <button type="submit" class="btn btn-success">Créer la formation</button>
        </form>
    </div>

    <!-- JS de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>