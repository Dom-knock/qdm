<?php

// Controleur : traiter_modifier_profil.php

// Role :
// traiter la modification du profil de l'utilisateur connecté

// Parametres :
// pseudo, email et éventuellement nouveau mot de passe reçus en POST

// Retour :
// met a jour le profil puis redirige vers la page profil

// charge l'initialisation générale de l'application
require_once "library/init.php";

// l'utilisateur doit être connecté
require_once "library/require_login.php";


// recuperation des données
$pseudo = "";

$email = "";

$password = "";

if (isset($_POST["pseudo"])) {
    $pseudo = trim($_POST["pseudo"]);
}

if (isset($_POST["email"])) {
    $email = trim($_POST["email"]);
}

if (isset($_POST["password"])) {
    $password = $_POST["password"];
}


// verification
if ($pseudo == "" || $email == "") {
    header("Location: afficher_formulaire_modifier_profil.php?erreur=champs");
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: afficher_formulaire_modifier_profil.php?erreur=email");
    exit;
}


// recuperation utilisateur
$utilisateur = userConnected();

$idUtilisateur = idConnected();
// verifie si le pseudo existe deja
if ($utilisateur->existePseudoAutreUtilisateur($pseudo, $idUtilisateur)) {
    header("Location: afficher_formulaire_modifier_profil.php?erreur=pseudo");
    exit;
}
// verifie si l'adresse email existe
if ($utilisateur->existeEmailAutreUtilisateur($email, $idUtilisateur)) {
    header("Location: afficher_formulaire_modifier_profil.php?erreur=email_existe");
    exit;
}

// modification des données
$utilisateur->set("pseudo", $pseudo);

$utilisateur->set("email", $email);


// le mot de passe est modifié uniquement
// si l'utilisateur en saisit un nouveau
if ($password != "") {

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $utilisateur->set("password", $passwordHash);
}


// mise à jour
$resultat = $utilisateur->update();

if (!$resultat) {
    header("Location: afficher_formulaire_modifier_profil.php?erreur=modification");
    exit;
}



// redirection vers la page de profil
header("Location: afficher_profil.php?modification=ok");
exit;
