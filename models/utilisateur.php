<?php
/*

Analyse du besoin :
Représenter un utilisateur enregistré
Responsable :
Classe modèle utilisateur.
Rôle :
Manipuler les données de la table utilisateur grâce aux méthodes héritées de _model.
Retour :
Cette classe ne produit aucun affichage.
Elle permet de créer, charger, modifier et supprimer un utilisateur.

*/

//charge la classe _model
require_once "core/_model.php";

class utilisateur extends _model
{
    //Cette classe herite de _model
    //$table    -->indique le nom de la base de données
    //$fields   -->indique les champs simple de la table
    //$links    -->indique les liens vers d'autre table

    protected $table = "utilisateur";
    protected $fields = ["pseudo", "email", "password"];
    protected $links = [];

    //methode pour verifier que le pseudo n'est pas deja utilisé
    function existePseudoAutreUtilisateur($pseudo, $idUtilisateur)
    {
        // role : vérifier si un pseudo est deja utilisé par un autre utilisateur
        // parametres :
        //      $pseudo : pseudo a verifier
        //      $idUtilisateur : id de l'utilisateur connecté
        // retour : true si le pseudo existe deja pour un autre utilisateur, false sinon

        $sql = "SELECT id
        FROM utilisateur
        WHERE pseudo = :pseudo
        AND id != :id";
        //preparation des parametres
        $param = [
            ":pseudo" => $pseudo,
            ":id" => $idUtilisateur
        ];

        $req = $this->execute($sql, $param);

        if (!$req) {
            return false;
        }

        $resultat = $req->fetch(PDO::FETCH_ASSOC);

        if ($resultat) {
            return true;
        }

        return false;
    }

    //methode pour verifier que l'adresse email n'est pas deja utilisè
    function existeEmailAutreUtilisateur($email, $idUtilisateur)
    {
        // role : verifier si un email est deja utilisé par un autre utilisateur
        // parametres :
        //      $email : email a verifier
        //      $idUtilisateur : id de l'utilisateur connecté
        // retour : true si l'email existe deja pour un autre utilisateur, false sinon

        $sql = "SELECT id FROM utilisateur WHERE email = :email AND id != :id";
        //preparation des parametres
        $param = [
            ":email" => $email,
            ":id" => $idUtilisateur
        ];

        $req = $this->execute($sql, $param);

        if (!$req) {
            return false;
        }

        $resultat = $req->fetch(PDO::FETCH_ASSOC);

        if ($resultat) {
            return true;
        }

        return false;
    }
}
