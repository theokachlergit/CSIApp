<?php
session_start();
require '../databases/database.php';
require '../entity/produit.php';

if (!isset($_SESSION['email'])) {
    header("Location: loginPage.php");
    exit();
}

if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] != 'Responsable') {
        header("Location: ../view/GestProduit.php");
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["modifierProduit"])) {
        $idProduit = $_POST["idProduit"];
        $nouveauNom = $_POST["nouveauNom"];
        $nouveauPrix = $_POST["nouveauPrix"];
        $nouveauType = $_POST["nouveauType"];

        Produit::mettreAJourNom($idProduit, $nouveauNom);
        Produit::mettreAJourPrix($idProduit, $nouveauPrix);
        Produit::mettreAJourType($idProduit, $nouveauType);
        }

    if (isset($_POST["ajouterProduit"])) {
        $nouveauNom = $_POST["nouveauNom"];
        $nouveauPrix = $_POST["nouveauPrix"];
        $nouveauType = $_POST["nouveauType"];
        if ($nouveauPrix>0){
            Produit::ajouterProduit($nouveauNom, $nouveauPrix, $nouveauType);
        }

    }
}



?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Produits - Eco-Ferme</title>
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
        <h2 class="mb-3">Gestion des Produits</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prix Unitaire</th>
                    <th>Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach (Produit::getAllProducts() as $produit): ?>
                    <tr>
                        <td><?= htmlspecialchars($produit->getNom()) ?></td>
                        <td><?= htmlspecialchars($produit->getPrixUnitaire()) ?></td>
                        <td><?= htmlspecialchars($produit->getType()->value) ?></td>
                        <td>
                            <button type="button" class="btn btn-blue" data-bs-toggle="modal" data-bs-target="#modifier-<?= $produit->getId() ?>">
                                Modifier
                            </button>
                            <div class="modal fade" id="modifier-<?= $produit->getId() ?>" tabindex="-1" aria-labelledby="modifierLabel-<?= $produit->getId() ?>" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modifierLabel-<?= $produit->getId() ?>">Modifier le produit</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="POST" ">
                                                <input type="hidden" name="idProduit" value="<?= $produit->getId() ?>"> <!-- ID du produit -->

                                                <div class="mb-3">
                                                    <label for="nouveauNom-<?= $produit->getId() ?>" class="form-label">Nom</label>
                                                    <input type="text" class="form-control" id="nouveauNom-<?= $produit->getId() ?>" name="nouveauNom" value="<?= htmlspecialchars($produit->getNom()) ?>">
                                                </div>

                                                <div class="mb-3">
                                                    <label for="nouveauPrix-<?= $produit->getId() ?>" class="form-label">Prix Unitaire</label>
                                                    <input type="number" class="form-control" id="nouveauPrix-<?= $produit->getId() ?>" name="nouveauPrix" value="<?= htmlspecialchars($produit->getPrixUnitaire()) ?>">
                                                </div>

                                                <div class="mb-3">
                                                    <label for="nouveauType-<?= $produit->getId() ?>" class="form-label">Type</label>
                                                    <select class="form-select" id="nouveauType-<?= $produit->getId() ?>" name="nouveauType">
                                                        <option value="">-- Sélectionner un type de produit --</option>
                                                        <?php foreach (enumTypeProduit::cases() as $typeProduit): ?>
                                                            <option value="<?= $typeProduit->name ?>" <?= $produit->getType()->name === $typeProduit->name ? 'selected' : '' ?>>
                                                                <?= $typeProduit->name ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-green" name="modifierProduit" value="1">Valider les modifications</button>
                                                    <button type="button" class="btn btn-red" data-bs-dismiss="modal">Fermer</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>

                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <button class="btn btn-green" data-bs-toggle="modal" data-bs-target="#ajouterProduit">Ajouter un Produit</button>
        <div class="modal fade" id="ajouterProduit" tabindex="-1" aria-labelledby="ajouterLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ajouterLabel">Ajouter un produit</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="nomProduit" class="form-label">Nom</label>
                                <input type="text" class="form-control" id="nomProduit" name="nouveauNom">
                            </div>
                            <div class="mb-3">
                                <label for="prixProduit" class="form-label">Prix Unitaire</label>
                                <input type="number" step="0.01" class="form-control" id="prixProduit" name="nouveauPrix">
                            </div>
                            <div class="mb-3">
                                <label for="typeProduit" class="form-label">Type</label>
                                <select class="form-select" id="typeProduit" name="nouveauType">
                                    <option value="">-- Sélectionner un type de produit --</option>
                                    <?php foreach (enumTypeProduit::cases() as $typeProduit): ?>
                                        <option value="<?= $typeProduit->name ?>"><?= $typeProduit->name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-green" name="ajouterProduit" value="1">Ajouter</button>
                            <button type="button" class="btn btn-red" data-bs-dismiss="modal">Annuler</button>
                        </div>
                    </form>
                </div>
            </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>