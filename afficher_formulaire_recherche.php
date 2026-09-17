<?php

// Controleur :
// afficher_formulaire_recherche.php

// Role :
// recuperer les criteres de recherche et rechercher les annonces correspondantes

// Parametres :
// criteres reçus en GET

// Retour :
// affiche le formulaire et les resultats de la recherche

// charge l'initialisation générale de l'application
require_once "library/init.php";

// recuperation des criteres
$motCle = "";
$categorie = "";
$etat = "";
$prixMax = "";
$vente = "";

if (isset($_GET["mot_cle"])) {
    $motCle = trim($_GET["mot_cle"]);
}

if (isset($_GET["categorie"])) {
    $categorie = $_GET["categorie"];
}

if (isset($_GET["etat"])) {
    $etat = $_GET["etat"];
}

if (isset($_GET["prix_max"])) {
    $prixMax = $_GET["prix_max"];
}

if (isset($_GET["vente"])) {
    $vente = $_GET["vente"];
}

// recuperation des categories
$url = "https://api.mywebecom.ovh/play/qdm/categ.php";

$json = file_get_contents($url);

$categories = json_decode($json, true);



// recherche des annonces
$modeleAnnonce = new annonce();

$resultats = $modeleAnnonce->rechercher(
    $motCle,
    $categorie,
    $etat,
    $prixMax,
    $vente
);


// affichage de la page de recherche
require "templates/pages/rechercher.php";
