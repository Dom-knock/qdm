<?php

// Controleur :
// afficher_tableau_bord.php

// Role :
// afficher le tableau de bord de l'utilisateur connecté

// Parametres :
// aucun

// Retour :
// affiche les annonces de l'utilisateur

// charge l'initialisation générale de l'application
require_once "library/init.php";

// l'utilisateur doit être connecté pour vendre
require_once "library/require_login.php";

$modeleAnnonce = new annonce();
$annonces = $modeleAnnonce->getAnnoncesUtilisateur(idConnected());

$modeleEnchere = new enchere();
$mesEncheres = $modeleEnchere->getEncheresUtilisateur(idConnected());
$encheresRemportees = $modeleEnchere->getEncheresRemportees(idConnected());

$modeleSuivi = new suivi();
$mesSuivis = $modeleSuivi->getSuivisUtilisateur(idConnected());

// afficher le template du tableau de bord
require "templates/pages/tableau_bord.php";
