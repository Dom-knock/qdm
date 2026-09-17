<?php

// Controleur :
// traiter_supprimer_annonce.php

// Role :
// supprimer une annonce appartenant à l'utilisateur connecté

// Parametre :
// id de l'annonce reçu en GET

// Retour :
// redirection vers l'accueil


// charge l'initialisation générale de l'application
require_once "library/init.php";

// l'utilisateur doit être connecté
require_once "library/require_login.php";


$idAnnonce = 0;

if (isset($_GET["id"])) {
    $idAnnonce = intval($_GET["id"]);
}


if ($idAnnonce <= 0) {
    header("Location: index.php");
    exit;
}


// charge l'annonce
$annonce = new annonce($idAnnonce);


// verifie qu'elle existe
if (!$annonce->is()) {
    header("Location: index.php");
    exit;
}


// verifie que l'utilisateur connecté est bien le propriétaire
if ($annonce->get("utilisateur") != idConnected()) {
    header("Location: index.php");
    exit;
}
$modeleEnchere = new enchere();

if ($modeleEnchere->existePourAnnonce($idAnnonce)) {
    header("Location: afficher_annonce.php?id=" . $idAnnonce);
    exit;
}

// supprime la photo liée à l'annonce si elle existe
$photo = new photo();

if ($photo->loadBy("annonce", $idAnnonce)) {

    $cheminPhoto = $photo->get("fichier");

    // supprime le fichier physique
    if ($cheminPhoto != null) {

        if (file_exists($cheminPhoto)) {
            unlink($cheminPhoto);
        }
    }

    // supprime la ligne dans la table photo
    $photo->delete();
}


// supprime l'annonce
if (!$annonce->delete()) {
    header("Location: afficher_annonce.php?id=" . $idAnnonce);
    exit;
}

//retour vers la page d'accueil
header("Location: index.php?suppression=ok");
exit;
