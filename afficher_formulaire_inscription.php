<?php
/*
Type de fichier :
Controleur.

Analyse du besoin :
Afficher le formulaire permettant à un nouvel utilisateur de créer un compte.

Responsable :
Controleur d'inscription.

Role :
Initialiser l'application puis appeler le template du formulaire d'inscription.

Parametre :
Aucune donnée utilisateur directe.

Retour attendu :
Affiche le template inscription.php.

*/
// Charge l'initialisation générale de l'application.
require_once "library/init.php";

// Appelle le template chargé d'afficher la page d'inscription
require "templates/pages/formulaire_inscription.php";
