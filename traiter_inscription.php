<?php
/*
Type de fichier :
Controleur

Analyse du besoin :
creer un nouveau compte utilisateur a partir des informations saisies
dans le formulaire d'inscription

Responsable :
controleur d'enregistrement de l'inscription

Role :
recuperer les informations du formulaire, vérifier leur validité,
créer un nouvel utilisateur puis l'enregistrer dans la base de données

Parametres :
- $_POST["pseudo"]
- $_POST["email"]
- $_POST["password"]
- $_POST["confirmationPassword"]

Retour attendu :
- reaffiche le formulaire avec un message d'erreur si une vérification échoue
- crer un nouvel utilisateur si les informations sont valides
- affiche la page de connexion après la création du compte
*/

// Charge l'initialisation générale de l'application
require_once "library/init.php";

// recupere les données envoyées par le formulaire
$pseudo = empty($_POST["pseudo"]) ? "" : trim($_POST["pseudo"]);

$email = empty($_POST["email"]) ? "" : trim($_POST["email"]);

$password = empty($_POST["password"]) ? "" : $_POST["password"];

$confirmationPassword = empty($_POST["confirmationPassword"]) ? "" : $_POST["confirmationPassword"];

// verifie que tous les champs obligatoires sont renseignés
if (
    empty($pseudo) || empty($email) || empty($password) || empty($confirmationPassword)
) {
    $message = "Tous les champs doivent être renseignés.";
    require "templates/pages/formulaire_inscription.php";
    exit;
}

// verifie le format de l'adresse email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $message = "L'adresse email n'est pas valide.";
    require "templates/pages/formulaire_inscription.php";
    exit;
}

// verifie que les deux mots de passe correspondent
if ($password !== $confirmationPassword) {
    $message = "Les mots de passe ne correspondent pas.";
    require "templates/pages/formulaire_inscription.php";
    exit;
}

// verifie le pseudo et l'email
$verifUtilisateur = new utilisateur();

if ($verifUtilisateur->loadBy("pseudo", $pseudo)) {
    $message = "Ce pseudo est déjà utilisé.";
    require "templates/pages/formulaire_inscription.php";
    exit;
}

if ($verifUtilisateur->loadBy("email", $email)) {
    $message = "Cette adresse email est déjà utilisée.";
    require "templates/pages/formulaire_inscription.php";
    exit;
}

// crée le nouvel utilisateur
$utilisateur = new utilisateur();

$utilisateur->set("pseudo", $pseudo);
$utilisateur->set("email", $email);

$utilisateur->set("password", password_hash($password, PASSWORD_DEFAULT));

// enregistre l'utilisateur dans la base de données
$utilisateur->insert();

// prépare le message de confirmation
$message = "Compte créé, tu peux maintenant te connecter.";

// affiche la page de connexion
require "templates/pages/formulaire_connexion.php";
