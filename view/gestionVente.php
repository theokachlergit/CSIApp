<?php
session_start();
require '../databases/database.php'; // Inclusion de la connexion à la BDD
require "../entity/Vente.php";
require "../enum/EMethodePaiement.php";

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['email'])) {
    header("Location: loginPage.php");
    exit();
}

if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] != 'Responsable') {
        header("Location: ../view/gestAtelier.php");
        exit();
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
    <h2 class="mb-3">Consultation des ventes</h2>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Prix de Vente</th>
            <th>Méthode de paiement</th>
            <th>Email Woofer</th>
            <th>Produits</th>

        </tr>
        </thead>
        <tbody>
        <?php foreach (Vente::getAllVentes() as $vente): ?>
            <tr>
                <td><?= htmlspecialchars($vente->getPrixVente()) ?></td>
                <td><?= htmlspecialchars($vente->getMethodePaiement()->value) ?></td>
                <td><?= htmlspecialchars($vente->getEmailWoofer()) ?></td>
                <td>
                    <button type="button" class="btn btn-blue" data-bs-toggle="modal" data-bs-target="#detail-<?= $vente->getIdVente() ?>">
                        Détails
                    </button>

                    <div class="modal fade" id="detail-<?= $vente->getIdVente() ?>" tabindex="-1" aria-labelledby="detailLabel-<?= $vente->getIdVente() ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="detailLabel-<?= $vente->getIdVente() ?>">Détails de la vente </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                </div>
                                <div class="modal-body">
                                    <h6>Produits :</h6>
                                    <ul>
                                        <?php foreach ($vente->getProduits() as $produit): ?>
                                            <li>
                                                <strong>Libelle Produit:</strong> <?= htmlspecialchars($produit['libelleProduit']) ?><br>
                                                <strong>Quantité:</strong> <?= htmlspecialchars($produit['quantiteVente']) ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-red" data-bs-dismiss="modal">Fermer</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>

            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>