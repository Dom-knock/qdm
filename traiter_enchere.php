<?php

// Controleur :
// traiter_enchere.php

// Role :
// enregistrer une enchère sur une annonce

// Parametres :
//      annonce : identifiant de l'annonce
//      montant : montant proposé

// Retour :
// redirection vers l'annonce

// charge l'initialisation générale de l'application
require_once "library/init.php";

// l'utilisateur doit être connecté
require_once "library/require_login.php";



// recuperation des données
$idAnnonce = 0;
$montant = 0;

if (isset($_POST["annonce"])) {
    $idAnnonce = intval($_POST["annonce"]);
}

if (isset($_POST["montant"])) {
    $montant = $_POST["montant"];
}



// verifications de base
if ($idAnnonce <= 0) {
    header("Location: index.php");
    exit;
}

if (!is_numeric($montant) || $montant <= 0) {
    header("Location: afficher_annonce.php?id=" . $idAnnonce);
    exit;
}



// charge l'annonce
$annonce = new annonce($idAnnonce);

if (!$annonce->is()) {
    header("Location: index.php");
    exit;
}



// verifie le propriétaire

// Le vendeur ne peut pas enchérir sur sa propre annonce
if ($annonce->get("utilisateur") == idConnected()) {
    header("Location: afficher_annonce.php?id=" . $idAnnonce);
    exit;
}



// verifie la date de fin
if (strtotime($annonce->get("date_heure_fin")) <= time()) {
    header("Location: afficher_annonce.php?id=" . $idAnnonce);
    exit;
}


// recherche le prix actuel
$modeleEnchere = new enchere();

$meilleureEnchere = $modeleEnchere->getMeilleureEnchere($idAnnonce);


// si il n'y a encore aucune enchere,
// le prix actuel correspond au prix de départ
if ($meilleureEnchere == null) {
    $prixActuel = $annonce->get("prix_depart");
} else {
    $prixActuel = $meilleureEnchere;
}



// verifie le montant proposé
if ($montant <= $prixActuel) {
    header("Location: afficher_annonce.php?id=" . $idAnnonce);
    exit;
}


// creation de l'enchere
$nouvelleEnchere = new enchere();

$nouvelleEnchere->set("montant", $montant);
$nouvelleEnchere->set("date_heure", date("Y-m-d H:i:s"));
$nouvelleEnchere->set("utilisateur", idConnected());
$nouvelleEnchere->set("annonce", $idAnnonce);

if (!$nouvelleEnchere->insert()) {
    header("Location: afficher_annonce.php?id=" . $idAnnonce);
    exit;
}


// enchere enregistrée avec succès
header("Location: afficher_annonce.php?id=" . $idAnnonce . "&enchere=ok");
exit;
