<?php

// Code a inclure dans les controleurs pour imposer la connexion
// Si on n'est pas connecte, ce code renvoie sur le formulaire de connexion

if (! isConnected()) {
    // Affiche le formulaire de connexion et sort
    // Parametre facultatif : $message
    $message = "Vous devez être connecté";
    require_once "templates/pages/formulaire_connexion.php";
    exit;       // Fin du controleur
}
