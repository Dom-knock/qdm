<?php
/*
Analyse du besoin :
Gérer le suivi des annonces par les utilisateurs.

Responsable :
Classe suivi.

Role :
Gérer la relation entre un utilisateur et une annonce suivie.

Retour :
Cette classe ne produit aucun affichage.
Elle permet d'ajouter, supprimer et vérifier le suivi d'une annonce
par un utilisateur.
*/

//charge la classe _model
require_once "core/_model.php";

class suivi extends _model
{
    //$table    -->indique le nom de la base de données
    //$fields   -->indique les champs simple de la table
    //$links    -->indique les liens vers d'autre table
    protected $table = "suivi";
    protected $fields = ["utilisateur", "annonce"];
    protected $links = ["utilisateur" => "utilisateur", "annonce" => "annonce"];


    // role : vérifier si un utilisateur suit déjà une annonce
    // paramètres :
    //      $idUtilisateur : id de l'utilisateur
    //      $idAnnonce : id de l'annonce
    // retour : true si le suivi existe, false sinon
    function existe($idUtilisateur, $idAnnonce)
    {
        // recherche d'un suivi correspondant a l'utilisateur et a l'annonce
        $sql = "SELECT `utilisateur` FROM `suivi` WHERE `utilisateur` = :utilisateur AND `annonce` = :annonce";
        //preparation des parametre de la requete
        $param = [
            ":utilisateur" => $idUtilisateur,
            ":annonce" => $idAnnonce
        ];
        // execution de la requete grace à la methode execute() hérité de _model
        $req = $this->execute($sql, $param);
        //si la requete n'a pas ete executer
        if (!$req) {
            return false;
        }
        // recuperation de la ligne trouvé
        $resultat = $req->fetch(PDO::FETCH_ASSOC);
        //si une ligne trouvé le suivi existe
        if ($resultat) {
            return true;
        }
        // pas de ligne trouvé le suivi n'existe pas
        return false;
    }

    // role : ajouter le suivi d'une annonce par un utilisateur
    // parametres :
    //      $idUtilisateur : id de l'utilisateur qui souhaite suivre l'annonce
    //      $idAnnonce : id de l'annonce à suivre
    // retour : true si le suivi a été ajouté, false sinon

    function ajouter($idUtilisateur, $idAnnonce)
    {
        // verifi si l'utilisateur suit deja cette annonce
        if ($this->existe($idUtilisateur, $idAnnonce)) {
            return false;
        }

        $sql = "INSERT INTO suivi (utilisateur, annonce) VALUES (:utilisateur, :annonce)";
        //preparation des parametres
        $param = [
            ":utilisateur" => $idUtilisateur,
            ":annonce" => $idAnnonce
        ];

        $req = $this->execute($sql, $param);

        if (!$req) {
            return false;
        }

        return true;
    }

    // role : supprimer le suivi d'une annonce par un utilisateur
    // parametres :
    //      $idUtilisateur : id de l'utilisateur
    //      $idAnnonce : id de l'annonce
    // retour : true si le suivi a été supprimé, false sinon
    function supprimer($idUtilisateur, $idAnnonce)
    {
        //supprime le suivi correspondant à l'utilisateur et a l'annonce
        $sql = "DELETE FROM suivi WHERE utilisateur = :utilisateur AND annonce = :annonce";
        //preparation des parametres
        $param = [
            ":utilisateur" => $idUtilisateur,
            ":annonce" => $idAnnonce
        ];

        //execution de la requete
        $req = $this->execute($sql, $param);

        //si la requête échoue
        if (!$req) {
            return false;
        }
        return true;
    }

    function getSuivisUtilisateur($idUtilisateur)
    {
        // role : recuperer les annonces suivies par un utilisateur
        // parametre : $idUtilisateur
        // retour : tableau contenant les annonces suivies

        $sql = "SELECT
            annonce.id,
            annonce.titre,
            annonce.etat,
            annonce.date_heure_fin,
            photo.fichier,
            COALESCE(MAX(enchere.montant), annonce.prix_depart) AS prix_actuel,
            COUNT(enchere.id) AS nombre_encheres
        FROM suivi
        INNER JOIN annonce
            ON suivi.annonce = annonce.id
        LEFT JOIN enchere
            ON annonce.id = enchere.annonce
        LEFT JOIN photo
            ON annonce.id = photo.annonce
            AND photo.principale = 1
        WHERE suivi.utilisateur = :utilisateur
        GROUP BY
            annonce.id,
            annonce.titre,
            annonce.etat,
            annonce.date_heure_fin,
            annonce.prix_depart,
            photo.fichier
        ORDER BY annonce.date_heure_fin ASC";
        //preparation des parametres
        $param = [
            ":utilisateur" => $idUtilisateur
        ];

        return $this->sqlToArray($sql, $param);
    }
}
