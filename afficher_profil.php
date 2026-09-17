<?php

// Controleur :
// afficher_profil.php

// Role :
// afficher le profil de l'utilisateur connecté

// Parametres :
// aucun

// Retour :
// affiche la page profil

// charge l'initialisation générale de l'application
require_once "library/init.php";

// l'utilisateur doit être connecté
require_once "library/require_login.php";

$utilisateur = userConnected();

// on appelle la page de profil de l'utilisateur
require "templates/pages/profil.php";
