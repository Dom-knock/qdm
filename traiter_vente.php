<?php

/*
Analyse du besoin :
traiter les données envoyées par le formulaire de vente
et créer une nouvelle annonce

Responsable :
le controleur traiter_vente.php

Role :
verifier les données du formulaire puis enregistrer
la nouvelle annonce dans la base de donnée

Parametres :
données reçues en POST

Retour :
redirige vers l'accueil si la creation réussit,
sinon on reaffiche le formulaire avec un message d'erreur
*/

// charge l'initialisation générale de l'application
require_once "library/init.php";

// l'utilisateur doit être connecté
require_once "library/require_login.php";


// recuperation des données du formulaire
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


// verifie que tous les champs obligatoires sont remplis
if (empty($titre) || empty($categorie) || empty($description) || empty($etat) || empty($prixDepart) || empty($dateFin) || empty($heureFin)) {
    $message = "Tous les champs obligatoires doivent etre remplis.";
    require "templates/pages/formulaire_vente.php";
    exit;
}


// verifie que le prix de départ est valide
if (!is_numeric($prixDepart) || $prixDepart <= 0) {
    $message = "Le prix de départ doit etre supérieur à 0.";

    require "templates/pages/formulaire_vente.php";
    exit;
}


// regroupe la date et l'heure pour correspondre au champ date_heure_fin
$dateHeureFin = $dateFin . " " . $heureFin . ":00";


// verifie que la fin de l'enchère est dans le futur
if (strtotime($dateHeureFin) <= time()) {
    $message = "La date de fin doit etre dans le futur.";

    require "templates/pages/formulaire_vente.php";
    exit;
}


// prepare la photo si un fichier a été envoyé
$fichierPhoto = null;

if (isset($_FILES["photo"])) {

    // l'absence de photo est autorisée
    if ($_FILES["photo"]["error"] != UPLOAD_ERR_NO_FILE) {

        $fichierPhoto = $_FILES["photo"];
        $error = $fichierPhoto["error"];

        // verifie si le fichier est trop volumineux
        if (
            $error == UPLOAD_ERR_INI_SIZE
            || $error == UPLOAD_ERR_FORM_SIZE
        ) {
            $message = "La photo est trop volumineuse.";

            require "templates/pages/formulaire_vente.php";
            exit;
        }

        // verifie si une autre erreur d'upload s'est produite
        if ($error != UPLOAD_ERR_OK) {
            $message = "Le chargement de la photo a échoué.";

            require "templates/pages/formulaire_vente.php";
            exit;
        }
    }
}

// création de l'annonce
$annonce = new annonce();

$annonce->set("titre", $titre);
$annonce->set("categorie", $categorie);
$annonce->set("description", $description);
$annonce->set("etat", $etat);
$annonce->set("prix_depart", $prixDepart);
$annonce->set("date_heure_fin", $dateHeureFin);
$annonce->set("utilisateur", idConnected());


// enregistrement dans la base de donnée
if (!$annonce->insert()) {
    $message = "Une erreur est survenue lors de la creation de l'annonce.";

    require "templates/pages/formulaire_vente.php";
    exit;
}

// enregistre la photo si l'utilisateur en a choisi une
if ($fichierPhoto != null) {

    $photo = new photo();

    // lie la photo à l'annonce qui vient d'être créée
    $photo->set("annonce", $annonce->id());

    // avec une seule photo, elle est automatiquement principale
    $photo->set("principale", 1);

    // deplace le fichier et prépare son chemin
    if (!$photo->stocker(
        $fichierPhoto["tmp_name"],
        $fichierPhoto["name"]
    )) {
        $message = "Impossible d'enregistrer la photo.";

        require "templates/pages/formulaire_vente.php";
        exit;
    }

    // enregistre les informations de la photo dans la BDD
    if (!$photo->insert()) {
        $message = "La photo n'a pas pu être ajoutée";

        require "templates/pages/formulaire_vente.php";
        exit;
    }
}

//redirection vers la page d'accueil
header("Location: index.php");
exit;
