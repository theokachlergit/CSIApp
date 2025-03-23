<?php

function auth(): bool

{
    var_dump($_POST);
    require '../entity/Utilisateur.php';
    require '../enum/Role.php';
    $user = new Utilisateur($_POST['email'],  $_POST['password'], Role::Woofer);
    return $user->authentifier();
}

function logout(): void
{
    session_start();
    session_destroy(); // Détruit la session
    header("Location: ../view/loginPage.php"); // Redirige vers la page de connexion
    exit();
}

function modifyProfil($pdo): void
{
    require '../entity/Utilisateur.php';
    require '../entity/Woofer.php';
    require '../entity/Personne.php';
    $user = new Utilisateur($_SESSION['email'], $_POST['mdpUtilisateur'], $_SESSION['role']);
    $personne = new Personne($_POST['nom'], $_POST['prenom'], $_POST['numTel']);
    $statement = $pdo->prepare("SELECT * FROM woofer WHERE emailPersonneUtilisateur = ?");
    $statement->bindParam(1, $_SESSION['email'], PDO::PARAM_STR);
    $statement->execute();
    $wooferTemp = $statement->fetch();
    $woofer = new Woofer($_POST['adresseWoofer'], $wooferTemp['photoWoofer'], new DateTime($wooferTemp['dateDebSejour']), new DateTime($wooferTemp['dateFinSejour']));
    $personne->modifierProfil($pdo);
    $user->modifierProfil($pdo);
    $woofer->modifierProfil($pdo);
    header("Location: ../view/profil.php");
}
function modifyProfilRes($pdo): void
{
    var_dump($_POST);
    require '../entity/Utilisateur.php';
    $user = new Utilisateur($_SESSION['email'], $_POST['mdpUtilisateur'], $_SESSION['role']);
    $user->modifierProfil($pdo);
}

function prolongerSejour($pdo): void
{
    require '../entity/Woofer.php';
    var_dump($_POST);
    $woofer = new Woofer("", "", new DateTime('01-01-01'), new DateTime($_POST['dateFinSejour']));
    $woofer->modifierInformations($pdo, $_POST['duree']);
}

function CreerWoofer($pdo): void
{
    require '../entity/Woofer.php';
    require '../entity/Utilisateur.php';
    require '../entity/Personne.php';
    require '../enum/Role.php';
    $woofer = new Woofer($_POST['adresseWoofer'], "", new DateTime($_POST['date_debut']), new DateTime($_POST['date_fin']));
    $utilisateur = new Utilisateur($_POST['email'], $_POST['password'], Role::Woofer);
    $personne = new Personne($_POST['nom'], $_POST['prenom'], $_POST['numTel']);
    $woofer->addWoofer($pdo);
    $personne->addPersonne($pdo);
    $utilisateur->addUtilisateur($pdo);
    header("Location: ../view/gestWoofer.php");
}
function getAllAteliers($pdo)
{
    $stmt = $pdo->query("SELECT * FROM atelier");
    $ateliers = $stmt->fetchAll();
    return $ateliers;
}

function getAtelierWithId($pdo, $id)
{
    $stmt = $pdo->prepare("SELECT * FROM atelier WHERE idAtelier = ? INNER JOIN Participe ON idAtelier = idAtelier INNER JOIN Inscrit ON emailPersonne = emailInscrit INNER JOIN personne ON emailPersonne = personne.email");
    $stmt->execute([$id]);
    $atelier = $stmt->fetch();
    return $atelier;
}

function getAllWoofers($pdo): array
{
    try {
        $statement = $pdo->prepare("SELECT * FROM woofer");
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}

function addAtelier($pdo): void
{
    require '../entity/Atelier.php';

    $idAtelier         = $_POST['idAtelier'] ?? null;
    $thematiqueAtelier = $_POST['thematiqueAtelier'];
    $typeProduit       = EnumTypeProduit::from($_POST['typeProduit']);
    $dateAtelier       = $_POST['dateAtelier'];
    $prixAtelier       = $_POST['prixAtelier'];
    $statutAtelier     = StatutAtelier::from($_POST['statut']);
    $emailWoofer       = $_POST['email'];

    var_dump($emailWoofer);
    $atelier = new Atelier($idAtelier, $thematiqueAtelier, $typeProduit, $dateAtelier, $prixAtelier, $statutAtelier, $emailWoofer);
    $atelier->creerAtelier($pdo);
    header("Location: ../view/gestAtelier.php");
}

function getAllPersonne($pdo) {
    $stmt = $pdo->query("SELECT * FROM personne");
    $personnes = $stmt->fetchAll();
    return $personnes;
}
