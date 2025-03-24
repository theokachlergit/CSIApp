<?php
session_start();
require '../databases/database.php';
require '../controller/AppController.php';
$pdo = Database::getConn();

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['email'])) {
    header("Location: loginPage.php");
    exit();
}

// Vérifier que l'id de l'atelier est passé dans l'URL
if (!isset($_GET['atelierId'])) {
    echo "Aucun atelier sélectionné.";
}

if (isset($_POST['annuler'])) {
    annulerAtelier($pdo);
    header("Location: gestAtelier.php");
    exit();
}
if (isset($_POST['terminer'])) {
    terminerAtelier($pdo);
    header("Location: gestAtelier.php");
    exit();
}
if (isset($_POST['Commencer'])) {
    CommencerAtelier($pdo);
    header("Location: gestAtelier.php");
    exit();
}
if (isset($_POST['changerDate'])) {
    changerDateAtelier($pdo);
    header("Location: gestAtelier.php");
    exit();
}
$idAtelier = intval($_GET['atelierId']);

// Requête pour récupérer les inscrits à l'atelier en effectuant les jointures
$query = "SELECT p.nom, p.prenom, p.email, p.numTel
          FROM atelier a
          INNER JOIN participe par ON a.idAtelier = par.idAtelier
          INNER JOIN inscrit i ON par.emailInscrit = i.emailPersonne
          INNER JOIN personne p ON i.emailPersonne = p.email
          WHERE a.idAtelier = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$idAtelier]);
$inscrits = $stmt->fetchAll(PDO::FETCH_ASSOC);

$statut = $pdo->query("SELECT * FROM atelier WHERE idAtelier = $idAtelier")->fetch(PDO::FETCH_ASSOC)['statutAtelier'];

if (isset($_POST['cancel'])) {
    require '../controller/AppController.php';
    desinscrire($pdo);
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscrits de l'Atelier <?= htmlspecialchars($idAtelier) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css.css">
</head>

<body>
    <?php
    if (isset($_SESSION['role'])) {
        if ($_SESSION['role'] != 'Responsable') {
            require 'nav-bar.html';
    ?>
            <div class="container my-4">
                <h2>Liste des inscrits pour l'atelier <?= htmlspecialchars($idAtelier) ?></h2>
                <?php if (count($inscrits) > 0) { ?>
                    <table class="table table-bordered">
                        <thead class="table-success">
                            <tr>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Email</th>
                                <th>Numéro de téléphone</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($inscrits as $inscrit) { ?>
                                <tr>
                                    <td><?= htmlspecialchars($inscrit['nom']) ?></td>
                                    <td><?= htmlspecialchars($inscrit['prenom']) ?></td>
                                    <td><?= htmlspecialchars($inscrit['email']) ?></td>
                                    <td><?= htmlspecialchars($inscrit['numTel']) ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    Statut actuelle <?php echo $statut ?>
                <?php } else { ?>
                    <p>Aucun inscrit trouvé pour cet atelier. Statut actuelle <?php echo $statut ?></p>
                    </p>
                <?php } ?>
            </div>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <?php
        } else {
            require 'nav-bar-admin.html';
        ?>
            <div class="container my-4">
                <h2>Liste des inscrits pour l'atelier <?= htmlspecialchars($idAtelier) ?></h2>
                <?php if (count($inscrits) > 0) { ?>
                    <table class="table table-bordered">
                        <thead class="table-success">
                            <tr>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Email</th>
                                <th>Numéro de téléphone</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($inscrits as $inscrit) { ?>
                                <tr>
                                    <td><?= htmlspecialchars($inscrit['nom']) ?></td>
                                    <td><?= htmlspecialchars($inscrit['prenom']) ?></td>
                                    <td><?= htmlspecialchars($inscrit['email']) ?></td>
                                    <td><?= htmlspecialchars($inscrit['numTel']) ?></td>
                                    <td>
                                        <form method="post">
                                            <input type="hidden" name="emailPersonne" value="<?= $inscrit['email'] ?>">
                                            <input type="hidden" name="idAtelier" value="<?= $idAtelier ?>">
                                            <button type="submit" name="cancel" class="btn btn-danger btn-sm">Annuler</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    Statut actuelle <?php echo $statut ?></p>
                    <form method="post">
                        <input type="hidden" name="atelierId" value="<?= $idAtelier ?>">
                        <button><a href="gestAtelier.php" class="btn btn-green">Retour</a></button>
                        <button type="submit" name="terminer" class="btn btn-success btn-sm">Terminer</button>
                        <button type="submit" name="Commencer" class="btn btn-warning btn-sm">Commencer</button>
                        <button type="button" class="btn btn-info btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#changerDateModal"
                            data-atelierid="<?= $idAtelier ?>">
                            Changer la date
                        </button>
                    </form>
                    <!-- Modal pour changer la date -->
                    <div class="modal fade" id="changerDateModal" tabindex="-1" aria-labelledby="changerDateModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="changerDateModalLabel">Changer la date de l'atelier</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                </div>
                                <div class="modal-body">
                                    <form method="post">
                                        <input type="hidden" name="atelierId" id="atelierIdChanger" value="">
                                        <div class="mb-3">
                                            <label for="nouvelleDate" class="form-label">Nouvelle date :</label>
                                            <input type="date" class="form-control" id="nouvelleDate" name="nouvelleDate" required>
                                        </div>
                                        <button type="submit" name="changerDate" class="btn btn-primary">Valider</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script>
                        var changerDateModal = document.getElementById('changerDateModal');
                        changerDateModal.addEventListener('show.bs.modal', function(event) {
                            var button = event.relatedTarget;
                            var atelierId = button.getAttribute('data-atelierid');
                            var inputAtelierId = changerDateModal.querySelector('#atelierIdChanger');
                            inputAtelierId.value = atelierId;
                        });
                    </script>
                <?php
                    $idAtelier;
                } else { ?>
                    <p>Aucun inscrit trouvé pour cet atelier. Statut actuelle <?php echo $statut ?></p>
                    <form method="post">
                        <input type="hidden" name="atelierId" value="<?= $idAtelier ?>">
                        <button><a href="gestAtelier.php" class="btn btn-green">Retour</a></button>
                        <button type="submit" name="terminer" class="btn btn-success btn-sm">Terminer</button>
                        <button type="submit" name="Commencer" class="btn btn-warning btn-sm">Commencer</button>
                        <button type="button" class="btn btn-info btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#changerDateModal"
                            data-atelierid="<?= $idAtelier ?>">
                            Changer la date
                        </button>
                    </form>
                    <!-- Modal pour changer la date -->
                    <div class="modal fade" id="changerDateModal" tabindex="-1" aria-labelledby="changerDateModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="changerDateModalLabel">Changer la date de l'atelier</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                </div>
                                <div class="modal-body">
                                    <form method="post">
                                        <input type="hidden" name="atelierId" id="atelierIdChanger" value="">
                                        <div class="mb-3">
                                            <label for="nouvelleDate" class="form-label">Nouvelle date :</label>
                                            <input type="date" class="form-control" id="nouvelleDate" name="nouvelleDate" required>
                                        </div>
                                        <button type="submit" name="changerDate" class="btn btn-primary">Valider</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script>
                        var changerDateModal = document.getElementById('changerDateModal');
                        changerDateModal.addEventListener('show.bs.modal', function(event) {
                            var button = event.relatedTarget;
                            var atelierId = button.getAttribute('data-atelierid');
                            var inputAtelierId = changerDateModal.querySelector('#atelierIdChanger');
                            inputAtelierId.value = atelierId;
                        });
                    </script>
                <?php } ?>
            </div>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php
        }
    }
    ?>
</body>

</html>