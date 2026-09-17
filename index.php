<?php
/*
Analyse du besoin :
afficher la page d'accueil.

Responsable :
controleur d'accueil.

Role :
 -initialiser l'application puis appeler le template chargé d'afficher la page d'accueil.
 -recuperer les annconces encours
Parametre :
aucun

Retour :
affichage du template accueil.php
*/

// charge l'initialisation générale de l'application
require_once "library/init.php";

// creation d'un objet annonce
$annonce = new annonce();

// recuperation des annonces encore en cours
$annonces = $annonce->getAnnoncesEnCours();

// on appelle le template chargé d'afficher la page d'accueil
require "templates/pages/accueil.php";
