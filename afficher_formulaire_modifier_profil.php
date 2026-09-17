<?php

// Controleur : afficher_formulaire_modifier_profil.php

// Role :
// afficher le formulaire de modification du profil

// Parametres :
// aucun

// Retour :
// affiche le formulaire prérempli

// charge l'initialisation générale de l'application
require_once "library/init.php";

// l'utilisateur doit être connecté
require_once "library/require_login.php";

$utilisateur = userConnected();

// afficher le formulaire pour modifier son profil
require "templates/pages/modifier_profil.php";
