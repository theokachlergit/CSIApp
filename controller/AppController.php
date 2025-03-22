<?php

function auth(): bool
{
    require_once '../entity/Utilisateur.php';
    $user = new Utilisateur();
    return $user->authentifier();
}

function logout(): void
{
    session_start();
    session_destroy(); // Détruit la session
    header("Location: ../view/loginPage.php"); // Redirige vers la page de connexion
    exit();
}

function modifyProfil(): void
{
    require_once '../entity/Utilisateur.php';
    $user = new Utilisateur();
    var_dump($_POST);
    $user->modifierProfil($_POST['mdpUtilisateur']);
}
