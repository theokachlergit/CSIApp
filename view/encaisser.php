<?php
session_start();
require '../databases/database.php'; // Inclusion de la connexion à la BDD
require '../entity/Produit.php';
require '../enum/EMethodePaiement.php';
require '../entity/Vente.php';

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['email'])) {
    header("Location: loginPage.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    if (isset($_POST['produitsEncaissement']) && isset($_POST['qte']) && isset($_POST['methodePaiementEncaissement']) && $_POST['methodePaiementEncaissement'] !== "") {
        $produits = $_POST['produitsEncaissement'];
        $quantites = $_POST['qte'];
        $methodePaiement = filter_var($_POST['methodePaiementEncaissement'], FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_EMPTY_STRING_NULL);
        $i = 0;
        $produitsVente = [];
        $prixVente = 0;
        foreach ($produits as $produit){
            $qteFiltre = filter_var($quantites[$i], FILTER_SANITIZE_NUMBER_INT);
            if ($qteFiltre >= 0) {
                $produitFiltre = filter_var($produit, FILTER_SANITIZE_NUMBER_INT);
                $produitsVente[$produitFiltre] = ($produitsVente[$produitFiltre] ?? 0) + $qteFiltre;
                $prixVente += Produit::getProductById($produitFiltre)->getPrixUnitaire() * $qteFiltre;
            }
            $i++;
        }

        $emailWoofer = filter_var($_SESSION['email'], FILTER_SANITIZE_EMAIL);
        $vente = new Vente(null, $prixVente, EMethodePaiement::from($methodePaiement), $emailWoofer, $produitsVente);
        $vente->enregistrerVente();
    }
    else{
        echo "<script>alert('Erreur, tous les champs doivent être remplis')</script>";
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
    if ($_SESSION['role'] != 'Administrateur') {
        require 'nav-bar.html';
    } else {
        require 'nav-bar-admin.html';
    }
}
?>

<div class="container mt-4">
    <h2 class="mb-3">Encaisser</h2>
    <form action="./encaisser.php" id="encaisserForm" method="post">
        <h4 class="mb-3">Méthode de paiement</h4>
        <select name="methodePaiementEncaissement" form="encaisserForm">
            <option value="">-- Veuillez choisir une méthode de paiement --</option>
            <?php foreach (EMethodePaiement::cases() as $moyenPaiement){
                echo "<option value={$moyenPaiement->value}>{$moyenPaiement->name}</option>";
            }
            ?>
        </select>
        <br>
        <br>
        <table class="table table-bordered" id="tableauDynamique">
            <thead>
            <tr>
                <th>Produit</th>
                <th>Quantité</th>
                <th>Action</th>
            </tr>
            </thead>
                <tbody>
                    <tr>
                        <td>
                            <select name="produitsEncaissement[]" form="encaisserForm">
                                <option value="">-- Veuillez choisir un produit --</option>
                                <?php foreach (Produit::getAllProducts() as $produit){
                                    echo "<option value={$produit->getId()}>{$produit->getNom()}</option>";
                                }?>
                            </select>
                        </td>
                        <td><input type="number" name="qte[]"></td>
                        <td><button type="button" class="btn supprimerLigne btn-red">Supprimer</button></td>
                    </tr>
                </tbody>
            </table>

        <button type="button" class="btn btn-green" id="ajouterLigne">Ajouter un Article</button>
        <button type="submit" class="btn btn-green">Valider Encaissement</button>
    </form>
    <script src="../javascript/tableauDynamique.js"></script>
</div>
</body>

</html>