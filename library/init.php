<?php
/*

Analyse du besoin :
Initialiser l'application avant l'execution d'un controleur.
Responsable :
Fichier d'initialisation.
Role :
Preparer les elements communs necessaires au fonctionnement du projet :
- affichage des erreurs pendant le developpement
- session utilisateur
- connexion a la base de donnees
- chargement des classes
Retour :
Ce fichier ne produit aucun affichage.
Il prepare l'environnement de travail pour les controleurs.

*/

// Affiche les erreurs PHP pendant le developpement.
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Charge les fonctions de session puis demarre la session.
require_once "session.php";
initSession();

// Declare la variable $bdd comme globale pour la rendre accessible aux modeles
global $bdd;

// Ouvre la connexion a la base de donnees.
$bdd = new PDO("mysql:host=172.18.0.1;dbname=qdm-dominique;charset=UTF8", "qdm-dominique", "V=52bpjrg");

// Configure PDO pour afficher les erreurs SQL pendant la mise au point.
$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

// Charge les classes metier de l'application.
require_once "core/_model.php";
require_once "models/utilisateur.php";
require_once "models/annonce.php";
require_once "models/enchere.php";
require_once "models/photo.php";
require_once "models/suivi.php";
