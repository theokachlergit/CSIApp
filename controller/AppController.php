<?php

function auth(): bool

{
    var_dump($_POST);
    require_once '../entity/Utilisateur.php';
    $user = new Utilisateur($_POST['email'],  $_POST['password'], "");
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
    require_once '../entity/Utilisateur.php';
    require_once '../entity/Woofer.php';
    require_once '../entity/Personne.php';
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
    require_once '../entity/Utilisateur.php';
    $user = new Utilisateur($_SESSION['email'], $_POST['mdpUtilisateur'], $_SESSION['role']);
    $user->modifierProfil($pdo);
}
