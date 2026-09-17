<?php

/*
Analyse du besoin :
afficher le formulaire permettant à un utilisateur connecté
de créer une annonce

Responsable :
le controleur afficher_formulaire_vente.php

Role :
verifier la connexion puis afficher le formulaire de vente

Parametre :
aucun.

Retour :
affiche le formulaire de creation d'annonce
*/

// charge l'initialisation general de l'application
require_once "library/init.php";

// verifie que l'utilisateur est connecté
require_once "library/require_login.php";

// adresse de l'API des categories
$url = "https://api.mywebecom.ovh/play/qdm/categ.php";

// recupère les catégories au format JSON
$json = file_get_contents($url);

// transforme le JSON en tableau PHP
$categories = json_decode($json, true);

// affiche le formulaire de vente
require "templates/pages/formulaire_vente.php";
