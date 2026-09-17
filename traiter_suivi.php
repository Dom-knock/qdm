<?php

// Controleur :
// traiter_suivi.php

// Role :
//ajouter ou supprimer le suivi d'une annonce pour l'utilisateur connecté

// Parametre :
// id de l'annonce reçu en GET

// Retour :
// redirection vers l'annonce

// charge l'initialisation générale de l'application
require_once "library/init.php";

// l'utilisateur doit être connecté
require_once "library/require_login.php";


// recupere l'identifiant de l'annonce
$idAnnonce = 0;

if (isset($_GET["id"])) {
    $idAnnonce = intval($_GET["id"]);
}


// verifie que l'identifiant est valide
if ($idAnnonce <= 0) {
    header("Location: index.php");
    exit;
}


// charge l'annonce
$annonce = new annonce($idAnnonce);

if (!$annonce->is()) {
    header("Location: index.php");
    exit;
}


// le vendeur ne peut pas suivre sa propre annonce
if ($annonce->get("utilisateur") == idConnected()) {
    header("Location: afficher_annonce.php?id=" . $idAnnonce);
    exit;
}


// gestion du suivi
$modeleSuivi = new suivi();

if ($modeleSuivi->existe(idConnected(), $idAnnonce)) {

    // l'annonce est deja suivie : on retire le suivi
    $modeleSuivi->supprimer(idConnected(), $idAnnonce);
} else {

    // l'annonce n'est pas encore suivie : on l'ajoute
    $modeleSuivi->ajouter(idConnected(), $idAnnonce);
}


// redirection vers l'annonce
header("Location: afficher_annonce.php?id=" . $idAnnonce);
exit;
