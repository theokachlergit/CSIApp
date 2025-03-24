<?php
session_start();
require '../databases/database.php'; // Inclusion de la connexion à la BDD
require "../entity/Produit.php";
// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['email'])) {
    header("Location: loginPage.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["maj"])) {
        $maj = $_POST["maj"];
        foreach ($maj as $idProduit => $qte) {
            if ($qte >= 0){
                Produit::mettreAJourStock($idProduit, $qte);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Stocks</title>
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

<div class="container mt-4">
    <h2 class="mb-3">Gestion des Stocks</h2>
    <form method="post">
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Nom</th>
            <th>Prix Unitaire</th>
            <th>Type</th>
            <th>Quantité</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach (Produit::getAllProducts() as $produit): ?>
            <tr>
                <td><?= htmlspecialchars($produit->getNom()) ?></td>
                <td><?= htmlspecialchars($produit->getPrixUnitaire()) ?></td>
                <td><?= htmlspecialchars($produit->getType()->value) ?></td>
                <td><input type="number" name="maj[<?= $produit->getId() ?>]" value="<?= htmlspecialchars($produit->getQuantiteStock()) ?>"></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <button class="btn btn-green" type="submit">Valider Modifications</button>
    </form>
</div>

</body>

</html>