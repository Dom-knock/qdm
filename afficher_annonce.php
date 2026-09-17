<?php

/*
Analyse du besoin :
afficher le detail d'une annonce choisie depuis la page d'accueil

Responsable :
le controleur voir_annonce.php

role :
recuperer l'annonce demandée puis afficher sa page de détail.

parametre :
id de l'annonce reçu en GET.

Retour :
affiche le détail de l'annonce.
*/

require_once "library/init.php";


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


// recupère l'annonce
$modeleAnnonce = new annonce();

$annonce = $modeleAnnonce->getAnnonceDetail($idAnnonce);


// verifie que l'annonce existe
if (!$annonce) {
    header("Location: index.php");
    exit;
}

// on recupere les categories depuis l'API
$url = "https://api.mywebecom.ovh/play/qdm/categ.php";

$json = file_get_contents($url);

$categories = json_decode($json, true);

// recherche le nom de la categorie de l'annonce
$nomCategorie = "";

if (isset($categories[$annonce["categorie"]])) {
    $nomCategorie = $categories[$annonce["categorie"]];
}

// verifie si l'annonce possede des encheres et recupere leur historique
// et on verifie que l'utlisateur est autosisé d'y acceder
$modeleEnchere = new enchere();
$aDesEncheres = $modeleEnchere->existePourAnnonce($idAnnonce);
$encheres = $modeleEnchere->getEncheresAnnonce($idAnnonce);
$peutVoirHistorique = false;

if (isConnected()) {

    if ($annonce["utilisateur"] == idConnected()) {
        $peutVoirHistorique = true;
    } else {

        if ($modeleEnchere->utilisateurAEncheri($idAnnonce, idConnected())) {
            $peutVoirHistorique = true;
        }
    }
}

// verifie si l'utilisateur connecté suit cette annonce
$estSuivie = false;

if (isConnected()) {

    if ($annonce["utilisateur"] != idConnected()) {

        $modeleSuivi = new suivi();

        $estSuivie = $modeleSuivi->existe(
            idConnected(),
            $idAnnonce
        );
    }
}

// affiche la page de détail d'une annonce
require "templates/pages/voir_annonce.php";
