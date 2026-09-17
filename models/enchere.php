<?php
/*

Analyse du besoin :
Représenter une enchère
Responsable :
Classe modèle enchere
Role :
Manipuler les données de la table enchere grace aux méthodes héritées de _model
Retour :
Cette classe ne produit aucun affichage
Elle permet de créer, charger, modifier et supprimer une enchère

*/

//charge la classe _model
require_once "core/_model.php";
class enchere extends _model
{
    //Cette classe herite de _model
    //$table    -->indique le nom de la table
    //$fields   -->indique les champs simple de la table
    //$links    -->indique les liens vers d'autre table

    protected $table = "enchere";
    protected $fields = ["montant", "date_heure", "utilisateur", "annonce"];
    protected $links = ["utilisateur" => "utilisateur", "annonce" => "annonce"];

    //methode pour recupere le montant de la meilleur affaire

    function getMeilleureEnchere($idAnnonce)
    {
        // Role : récupérer le montant de la meilleure enchère d'une annonce
        // Parametre : $idAnnonce
        // Retour : montant de la meilleure enchère ou null s'il n'y en a aucune

        $sql = "SELECT MAX(montant) AS montant_max
        FROM enchere
        WHERE annonce = :annonce
    ";

        $param = [
            ":annonce" => $idAnnonce
        ];

        $req = $this->execute($sql, $param);

        if (!$req) {
            return null;
        }

        $ligne = $req->fetch(PDO::FETCH_ASSOC);

        return $ligne["montant_max"];
    }

    //methode pour verifier qu'une enchere existe

    function existePourAnnonce($idAnnonce)
    {
        // role : verifier si une annonce possede au moins une enchere
        // parametre : $idAnnonce
        // retour : true s'il existe une enchere, false sinon

        $sql = "SELECT id FROM enchere WHERE annonce = :annonce LIMIT 1";

        $param = [
            ":annonce" => $idAnnonce
        ];

        $req = $this->execute($sql, $param);

        if (!$req) {
            return false;
        }

        $ligne = $req->fetch(PDO::FETCH_ASSOC);

        if (!$ligne) {
            return false;
        }

        return true;
    }

    //methode pour recupere toutes les encheres
    function getEncheresAnnonce($idAnnonce)
    {
        // role : récupérer toutes les encheres d'une annonce
        // parametre : $idAnnonce
        // retour : tableau contenant les encheres de l'annonce

        $sql = "SELECT enchere.id, enchere.montant, enchere.date_heure, utilisateur.pseudo
        FROM enchere
        INNER JOIN utilisateur
            ON enchere.utilisateur = utilisateur.id
        WHERE enchere.annonce = :annonce
        ORDER BY enchere.date_heure DESC
    ";

        $param = [
            ":annonce" => $idAnnonce
        ];

        return $this->sqlToArray($sql, $param);
    }

    //methode pour la visibilité d'une enchere
    function utilisateurAEncheri($idAnnonce, $idUtilisateur)
    {
        // role : verifier si un utilisateur a déjà enchéri sur une annonce
        // parametres :
        //      $idAnnonce : identifiant de l'annonce
        //      $idUtilisateur : identifiant de l'utilisateur
        // retour : true si l'utilisateur a déjà enchéri, false sinon

        $sql = "SELECT id FROM enchere WHERE annonce = :annonce AND utilisateur = :utilisateur LIMIT 1";

        $param = [
            ":annonce" => $idAnnonce,
            ":utilisateur" => $idUtilisateur
        ];

        $req = $this->execute($sql, $param);

        if (!$req) {
            return false;
        }

        $ligne = $req->fetch(PDO::FETCH_ASSOC);

        if (!$ligne) {
            return false;
        }

        return true;
    }

    //methode pour recuperer les annonces sur lesquells un utilisateur a encheri
    function getEncheresUtilisateur($idUtilisateur)
    {
        // role : recuperer les annonces sur lesquelles un utilisateur a encheri
        // parametre : $idUtilisateur
        // retour : tableau contenant les annonces concernées

        $sql = "SELECT
            annonce.id,
            annonce.titre,
            annonce.etat,
            annonce.date_heure_fin,
            photo.fichier,
            MAX(enchere.montant) AS meilleure_enchere_utilisateur,
            (
                SELECT MAX(e2.montant)
                FROM enchere e2
                WHERE e2.annonce = annonce.id
            ) AS prix_actuel
        FROM enchere
        INNER JOIN annonce
            ON enchere.annonce = annonce.id
        LEFT JOIN photo
            ON annonce.id = photo.annonce
            AND photo.principale = 1
        WHERE enchere.utilisateur = :utilisateur
        GROUP BY
            annonce.id,
            annonce.titre,
            annonce.etat,
            annonce.date_heure_fin,
            photo.fichier
        ORDER BY annonce.date_heure_fin ASC";

        $param = [
            ":utilisateur" => $idUtilisateur
        ];

        return $this->sqlToArray($sql, $param);
    }

    //methode pour voir une enchere remportée
    function getEncheresRemportees($idUtilisateur)
    {
        // role : recuperer les encheres remportées par un utilisateur
        // parametre : $idUtilisateur
        // retour : tableau contenant les annonces remportées

        $sql = "SELECT
            annonce.id,
            annonce.titre,
            annonce.date_heure_fin,
            photo.fichier,
            MAX(enchere.montant) AS prix_final
        FROM enchere
        INNER JOIN annonce
            ON enchere.annonce = annonce.id
        LEFT JOIN photo
            ON annonce.id = photo.annonce
            AND photo.principale = 1
        WHERE annonce.date_heure_fin <= NOW()
        AND enchere.utilisateur = :utilisateur
        AND enchere.montant = (
            SELECT MAX(e2.montant)
            FROM enchere AS e2
            WHERE e2.annonce = annonce.id)
        GROUP BY
            annonce.id,
            annonce.titre,
            annonce.date_heure_fin,
            photo.fichier
        ORDER BY annonce.date_heure_fin DESC";

        $param = [
            ":utilisateur" => $idUtilisateur
        ];

        return $this->sqlToArray($sql, $param);
    }
}
