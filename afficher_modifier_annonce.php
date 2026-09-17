<?php

/*
Analyse du besoin :
afficher le formulaire de modification d'une annonce

Responsable :
Le controleur afficher_modifier_annonce.php

Role :
charger l'annonce demandée, verifie que l'utilisateur connecté
est le proprietaire de l'annonce, puis afficher le formulaire prérempli

Parametre :
id de l'annonce reçu en GET

Retour :
affiche le formulaire de modification
*/

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


// charge directement l'annonce grace au constructeur du _model
$annonce = new annonce($idAnnonce);


// verifie que l'annonce existe
if (!$annonce->is()) {
    header("Location: index.php");
    exit;
}


// verifie que l'annonce appartient à l'utilisateur connecté
if ($annonce->get("utilisateur") != idConnected()) {
    header("Location: index.php");
    exit;
}

$modeleEnchere = new enchere();

if ($modeleEnchere->existePourAnnonce($idAnnonce)) {
    header("Location: afficher_annonce.php?id=" . $idAnnonce);
    exit;
}

// recupere les categories depuis l'API
$url = "https://api.mywebecom.ovh/play/qdm/categ.php";

$json = file_get_contents($url);

$categories = json_decode($json, true);


// affiche le formulaire de modification
require "templates/pages/modifier_annonce.php";
