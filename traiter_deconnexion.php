<?php
/*
Type de fichier :
Controleur de traitement

Analyse du besoin :
Déconnecter l'utilisateur actuellement connecté
puis lui réafficher la page d'accueil

Responsable :
Contrôleur de déconnexion

Role :
Mettre fin à la session de l'utilisateur
préparer un message de confirmation
puis afficher le formulaire de connexion

Parametre :
Aucun

Retour attendu :
Affiche la page d'accueil du site
*/

// charge l'environnement commun de l'application
require_once "library/init.php";

// met fin à la session de l'utilisateur connecté
// supprime les informations d'authentificatio
// de la session de l'utilisateur connecté
disconnect();

// Prépare un message destiné au template
$message = "Vous êtes bien déconnecté.";

// Le controleur delegue l'affichage au template de connexion
require "index.php";
