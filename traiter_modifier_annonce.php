<?php
//Analyse du besoin :
//traiter les données envoyées par le formulaire de modification

// Controleur : traiter_modifier_annonce.php

// Role : modifier une annonce appartenant à l'utilisateur connecté

// Parametres : données reçues en POST

// Retour : redirection vers l'annonce modifiée

// Charge l'initialisation générale de l'application
require_once "library/init.php";

// L'utilisateur doit être connecté pour vendre
require_once "library/require_login.php";


// recuperation de l'annonce

$idAnnonce = 0;

if (isset($_POST["id"])) {
    $idAnnonce = intval($_POST["id"]);
}

if ($idAnnonce <= 0) {
    header("Location: index.php");
    exit;
}

$annonce = new annonce($idAnnonce);


// verifie que l'annonce existe
if (!$annonce->is()) {
    header("Location: index.php");
    exit;
}


// verifie que l'utilisateur est bien propriétaire
if ($annonce->get("utilisateur") != idConnected()) {
    header("Location: index.php");
    exit;
}

$modeleEnchere = new enchere();

if ($modeleEnchere->existePourAnnonce($idAnnonce)) {
    header("Location: afficher_annonce.php?id=" . $idAnnonce);
    exit;
}

// recuperation du formulaire
$titre = "";
$categorie = "";
$description = "";
$etat = "";
$prixDepart = "";
$dateFin = "";
$heureFin = "";

if (isset($_POST["titre"])) {
    $titre = trim($_POST["titre"]);
}

if (isset($_POST["categorie"])) {
    $categorie = $_POST["categorie"];
}

if (isset($_POST["description"])) {
    $description = trim($_POST["description"]);
}

if (isset($_POST["etat"])) {
    $etat = $_POST["etat"];
}

if (isset($_POST["prix_depart"])) {
    $prixDepart = $_POST["prix_depart"];
}

if (isset($_POST["date_fin"])) {
    $dateFin = $_POST["date_fin"];
}

if (isset($_POST["heure_fin"])) {
    $heureFin = $_POST["heure_fin"];
}

// controles
if (
    $titre == "" ||
    $categorie == "" ||
    $description == "" ||
    $etat == "" ||
    $prixDepart == "" ||
    $dateFin == "" ||
    $heureFin == ""
) {
    $message = "Tous les champs obligatoires doivent être remplis.";

    $url = "https://api.mywebecom.ovh/play/qdm/categ.php";
    $json = file_get_contents($url);
    $categories = json_decode($json, true);

    require "templates/pages/modifier_annonce.php";
    exit;
}


if (!is_numeric($prixDepart) || $prixDepart <= 0) {
    $message = "Le prix de départ doit être supérieur à zéro.";

    $url = "https://api.mywebecom.ovh/play/qdm/categ.php";
    $json = file_get_contents($url);
    $categories = json_decode($json, true);

    require "templates/pages/modifier_annonce.php";
    exit;
}


// reconstitue le DATETIME
$dateHeureFin = $dateFin . " " . $heureFin . ":00";


if (strtotime($dateHeureFin) <= time()) {
    $message = "La date de fin doit être dans le futur.";

    $url = "https://api.mywebecom.ovh/play/qdm/categ.php";
    $json = file_get_contents($url);
    $categories = json_decode($json, true);

    require "templates/pages/modifier_annonce.php";
    exit;
}

// modification de l'annonce
$annonce->set("titre", $titre);
$annonce->set("categorie", $categorie);
$annonce->set("description", $description);
$annonce->set("etat", $etat);
$annonce->set("prix_depart", $prixDepart);
$annonce->set("date_heure_fin", $dateHeureFin);


// la propriété utilisateur ne change pas.
// elle est déjà presente dans l'objet chargé.

if (!$annonce->update()) {
    $message = "La modification de l'annonce a échoué.";

    $url = "https://api.mywebecom.ovh/play/qdm/categ.php";
    $json = file_get_contents($url);
    $categories = json_decode($json, true);

    require "templates/pages/modifier_annonce.php";
    exit;
}



// nouvelle photo eventuelle

if (isset($_FILES["photo"])) {

    if ($_FILES["photo"]["error"] != UPLOAD_ERR_NO_FILE) {

        if ($_FILES["photo"]["error"] == UPLOAD_ERR_OK) {

            $photo = new photo();

            // cherche si l'annonce possède deja une photo
            $photoExiste = $photo->loadBy("annonce", $idAnnonce);

            if (!$photoExiste) {
                $photo = new photo();
                $photo->set("annonce", $idAnnonce);
                $photo->set("principale", 1);
            }

            // stocke la nouvelle photo
            if ($photo->stocker(
                $_FILES["photo"]["tmp_name"],
                $_FILES["photo"]["name"]
            )) {

                if ($photoExiste) {
                    $photo->update();
                } else {
                    $photo->insert();
                }
            }
        }
    }
}


//retour vers la page annonce
header("Location: afficher_annonce.php?id=" . $idAnnonce);
exit;
