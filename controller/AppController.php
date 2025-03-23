<?php

function auth(): bool

{

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
    $personne = new Personne(
        $_SESSION['email'],
        $_POST['nom'],
        $_POST['prenom'],
        $_POST['numTel']
    );
    $statement = $pdo->prepare("SELECT * FROM woofer WHERE emailPersonneUtilisateur = ?");
    $statement->bindParam(1, $_SESSION['email'], PDO::PARAM_STR);
    $statement->execute();
    $wooferTemp = $statement->fetch();
    $woofer = new Woofer($_POST['adresseWoofer'], $wooferTemp['photoWoofer'], new DateTime($wooferTemp['dateDebSejour']), new DateTime($wooferTemp['dateFinSejour']), "", "", "", "");
    $personne->modifierProfil($pdo);
    $user->modifierProfil($pdo);
    $woofer->modifierProfil($pdo);
    header("Location: ../view/profil.php");
}
function modifyProfilRes($pdo): void
{

    require '../entity/Utilisateur.php';
    $user = new Utilisateur($_SESSION['email'], $_POST['mdpUtilisateur'], $_SESSION['role']);
    $user->modifierProfil($pdo);
}

function prolongerSejour($pdo): void
{
    require '../entity/Woofer.php';

    $woofer = new Woofer("", "", new DateTime('01-01-01'), new DateTime($_POST['dateFinSejour']), "", "", "", "");
    $woofer->prolongerSejour($pdo, $_POST['duree']);
}

function CreerWoofer($pdo): void
{
    require '../entity/Woofer.php';
    require '../entity/Utilisateur.php';
    require '../enum/Role.php';
    $personne = new Personne(
        $_POST['email'],
        $_POST['nom'],
        $_POST['prenom'],
        $_POST['numTel']
    );
    $woofer = new Woofer(
        $_POST['adresseWoofer'],
        "",
        new DateTime($_POST['date_debut']),
        new DateTime($_POST['date_fin']),
        $_POST['email'],
        $_POST['nom'],
        $_POST['prenom'],
        $_POST['numTel']
    );
    $utilisateur = new Utilisateur($_POST['email'], $_POST['mdpUtilisateur'], Role::Woofer);
    $personne->addPersonne($pdo);
    $utilisateur->creerUtilisateur($pdo);
    $woofer->creerWoofer($pdo);
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

    $atelier = new Atelier($idAtelier, $thematiqueAtelier, $typeProduit, $dateAtelier, $prixAtelier, $statutAtelier, $emailWoofer);
    $atelier->creerAtelier($pdo);
    header("Location: ../view/gestAtelier.php");
}

function getAllPersonne($pdo)
{
    $stmt = $pdo->query("SELECT emailPersonne AS email FROM inscrit");
    $personnes = $stmt->fetchAll();
    return $personnes;
}
function inscrire($pdo): void
{
    require '../entity/Atelier.php';
    require '../enum/statutAtelier.php';
    require '../enum/enumTypeProduit.php';
    $atelier = new Atelier($_POST['atelierId'], "", enumTypeProduit::Confiture, "", "", statutAtelier::EnCours, "");
    $atelier->inscrireParticipant($pdo);
    header("Location: ../view/gestAtelier.php");
}

function inscrireNew($pdo): void
{
    require '../entity/Atelier.php';
    require '../entity/Personne.php';
    $personne = new Personne(
        $_POST['email'],
        $_POST['nom'],
        $_POST['prenom'],
        $_POST['numTel']
    );
    var_dump($_POST['email'], $_POST['atelierId']);
    $personne->addPersonne($pdo);
    $pdo->query("INSERT INTO inscrit (emailPersonne) VALUES ('{$_POST['email']}')");
    require '../enum/statutAtelier.php';
    require '../enum/enumTypeProduit.php';
    $atelier = new Atelier($_POST['atelierId'], "", enumTypeProduit::Confiture, "", "", statutAtelier::EnCours, "");
    $atelier->inscrireParticipant($pdo);
    header("Location: ../view/gestAtelier.php");
}

function cancelAtelier($pdo): void
{
    require '../entity/Atelier.php';
    $atelier = Atelier::annulerAtelier($pdo);
    header("Location: ../view/gestAtelier.php");
}

function desinscrire($pdo): void
{
    var_dump([$_POST['emailPersonne'], $_POST['idAtelier']]);
    $stmt = $pdo->prepare("DELETE FROM participe WHERE emailInscrit = ? AND idAtelier = ?");
    $stmt->execute([$_POST['emailPersonne'], $_POST['idAtelier']]);
    // header("Location: ../view/inscritAtelier.php?atelierId={$_POST['idAtelier']}");
}
function annulerAtelier($pdo): void
{
    require '../entity/Atelier.php';
    require '../enum/statutAtelier.php';
    require '../enum/enumTypeProduit.php';
    $atelier = new Atelier($_POST['atelierId'], "", enumTypeProduit::Confiture, "", "", statutAtelier::EnCours, "");
    $atelier->annulerAtelier($pdo);
}
function CommencerAtelier($pdo): void
{
    require '../entity/Atelier.php';
    require '../enum/statutAtelier.php';
    require '../enum/enumTypeProduit.php';
    $atelier = new Atelier($_POST['atelierId'], "", enumTypeProduit::Confiture, "", "", statutAtelier::EnCours, "");
    $atelier->CommencerAtelier($pdo);
}

function terminerAtelier($pdo): void
{
    require '../entity/Atelier.php';
    var_dump($_POST);
    require '../enum/statutAtelier.php';
    require '../enum/enumTypeProduit.php';
    $atelier = new Atelier($_POST['atelierId'], "", enumTypeProduit::Confiture, "", "", statutAtelier::EnCours, "");
    $atelier->terminerAtelier($pdo);
}

function changerDateAtelier($pdo): void
{
    require '../entity/Atelier.php';
    require '../enum/statutAtelier.php';
    require '../enum/enumTypeProduit.php';
    var_dump($_POST);
    $date = DateTime::createFromFormat('Y-m-d', $_POST['nouvelleDate']);
    $atelier = new Atelier($_POST['atelierId'], "", enumTypeProduit::Confiture, "", "", statutAtelier::EnCours, "");
    $atelier->modifierDateAtelier($pdo, $date);
}
