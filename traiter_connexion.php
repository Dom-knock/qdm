<?php

/*
Type de fichier :
Controleur.

Analyse du besoin :
Traiter le formulaire de connexion d'un utilisateur.

Responsable :
Controleur de connexion.

Rôle :
verifier les identifiants saisis par l'utilisateur.
si les informations sont correctes, connecter l'utilisateur en session.

Parametre :
- $_POST["identifiant"]
- $_POST["password"]

Retour attendu :
- affiche a nouveau le template de connexion avec un message d'erreur si les données sont invalides.
-affiche le template de la page de connexion
*/

// charge l'initialisation générale de l'application
require_once "library/init.php";

// recupere l'identifiant saisi.
// Il peut contenir un pseudo ou une adresse email.
$identifiant = empty($_POST["identifiant"]) ? "" : trim($_POST["identifiant"]);
// Récupère le mot de passe.
$password = empty($_POST["password"]) ? "" : $_POST["password"];

// verifie que les deux champs sont renseignés.
if (empty($identifiant) || empty($password)) {
    $message = "Il faut saisir votre pseudo ou votre email ainsi que votre mot de passe.";

    require "templates/pages/formulaire_connexion.php";
    exit;
}

// crée un objet utilisateur pour effectuer la recherche.
$utilisateur = new utilisateur();

// cherche d'abord l'utilisateur par son adresse email.
$utilisateurTrouve = $utilisateur->loadBy("email", $identifiant);

// si aucun utilisateur n'a été trouvé par email,
// effectue une nouvelle recherche par pseudo.
if (!$utilisateurTrouve) {
    $utilisateur = new utilisateur();

    $utilisateurTrouve = $utilisateur->loadBy("pseudo", $identifiant);
}

// si aucune recherche n'a trouvé d'utilisateur,
// réaffiche le formulaire avec un message volontairement général.
if (!$utilisateurTrouve) {
    $message = "Identifiant ou mot de passe incorrect.";

    require "templates/pages/formulaire_connexion.php";
    exit;
}

// verifie que le mot de passe saisi correspond
// au mot de passe haché enregistré dans la base de données.
if (!password_verify($password, $utilisateur->get("password"))) {
    $message = "Identifiant ou mot de passe incorrect.";

    require "templates/pages/formulaire_connexion.php";
    exit;
}

// Connecte l'utilisateur en enregistrant son identifiant en session.
connect($utilisateur->get("id"));

// redirige vers l'accueil.
header("Location: index.php");
exit;
