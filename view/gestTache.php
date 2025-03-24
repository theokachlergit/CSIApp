<?php
session_start();
require '../databases/database.php';
require '../enum/enumTypeActivite.php';
// require '../entity/Tache.php'; // Fichier où se trouve la classe Tache

$pdo = Database::getConn();

// 1. Vérifier la présence du paramètre GET "email"
if (!isset($_GET['email'])) {
    echo "Aucun woofer spécifié.";
    exit;
}
$wooferEmail = $_GET['email'];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $activite = $_POST['activiteTache'] ?? null;
    if ($activite) {
        $typeActivite = TypeActivite::from($activite);
        $tache = new Tache($typeActivite);
        $tache->effectuerTache($pdo);
        header("Location: gestTacheWoofer.php?email=" . urlencode($wooferEmail));
        exit;
    }
}

$stmt = $pdo->prepare("SELECT activiteTache FROM effectue WHERE emailWoofer = ?");
$stmt->execute([$wooferEmail]);
$taches = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Tâches du woofer</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css.css">
</head>

<body>
    <div class="container">
        <h1>Tâches du Woofer (<?= htmlspecialchars($wooferEmail) ?>)</h1>
        <?php if ($taches): ?>
            <h2>Liste des tâches déjà assignées</h2>
            <ul>
                <?php foreach ($taches as $tache): ?>
                    <li><?= htmlspecialchars($tache['activiteTache']) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Aucune tâche assignée pour ce woofer.</p>
        <?php endif; ?>

        <hr>

        <h2>Ajouter une nouvelle tâche</h2>
        <form method="post">
            <div class="mb-3">
                <label for="activiteTache" class="form-label">Choisissez une activité :</label>
                <select name="activiteTache" id="activiteTache" required>
                    <?php foreach (TypeActivite::cases() as $activiteCase): ?>
                        <option value="<?= $activiteCase->value ?>">
                            <?= $activiteCase->value ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <input type="hidden" name="email" value="<?= htmlspecialchars($wooferEmail) ?>">
            <button type="submit" class="btn btn-primary">Ajouter</button>
            <a href="gestTacheWoofer.php" class=" btn btn-success">Retour</a>
        </form>
    </div>

</body>

</html>