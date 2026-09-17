<?php
/*

Analyse du besoin :
Représenter une annonce
Responsable :
Classe modèle annonce
Role :
Manipuler les données de la table annonce grace aux méthodes héritées de _model
Retour :
Cette classe ne produit aucun affichage
Elle permet de créer, charger, modifier et supprimer une annonce

*/

//charge la classe _model
require_once "core/_model.php";

class annonce extends _model
{
    //Cette classe herite de _model
    //$table    -->indique le nom de la table
    //$fields   -->indique les champs simple de la table
    //$links    -->indique les liens vers d'autre table

    protected $table = "annonce";
    protected $fields = ["titre", "categorie", "description", "etat", "prix_depart", "date_heure_fin", "utilisateur"];
    protected $links = ["utilisateur" => "utilisateur"];

    // methode pour recuperer les annonces en cours et les afficher sur la page d'accueil

    function getAnnoncesEnCours()
    {
        // role : recuperer les annonces encore en cours pour les afficher sur la page d'accueil
        // parametres : aucun
        // retour : tableau contenant les annonces en cours

        $sql = "SELECT annonce.id, annonce.titre, annonce.etat, annonce.prix_depart, annonce.date_heure_fin, annonce.utilisateur, photo.fichier, COALESCE(MAX(enchere.montant), annonce.prix_depart) AS prix_actuel
        FROM annonce LEFT JOIN enchere ON annonce.id = enchere.annonce LEFT JOIN photo ON annonce.id = photo.annonce
        AND photo.principale = 1
        WHERE annonce.date_heure_fin > NOW()
        GROUP BY annonce.id, annonce.titre, annonce.etat, annonce.prix_depart, annonce.date_heure_fin, annonce.utilisateur, photo.fichier
        ORDER BY annonce.date_heure_fin ASC";

        $param = [];

        $req = $this->execute($sql, $param);

        if (!$req) {
            return [];
        }

        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    // methode pour recuperer toutes les information sur une annonce
    function getAnnonceDetail($idAnnonce)
    {
        // role : recuperer les informations completes d'une annonce
        // parametre : $idAnnonce, identifiant de l'annonce
        // retour : tableau contenant l'annonce ou false si elle n'existe pas

        $sql = "SELECT
        annonce.id,
        annonce.titre,
        annonce.categorie,
        annonce.description,
        annonce.etat,
        annonce.prix_depart,
        annonce.date_heure_fin,
        annonce.utilisateur,
        utilisateur.pseudo AS vendeur,
        photo.fichier,
        COALESCE(MAX(enchere.montant), annonce.prix_depart) AS prix_actuel
    FROM annonce
    INNER JOIN utilisateur
        ON annonce.utilisateur = utilisateur.id
    LEFT JOIN enchere
        ON annonce.id = enchere.annonce
    LEFT JOIN photo
        ON annonce.id = photo.annonce
        AND photo.principale = 1
    WHERE annonce.id = :id
    GROUP BY
        annonce.id,
        annonce.titre,
        annonce.categorie,
        annonce.description,
        annonce.etat,
        annonce.prix_depart,
        annonce.date_heure_fin,
        annonce.utilisateur,
        utilisateur.pseudo,
        photo.fichier";

        $param = [
            ":id" => $idAnnonce
        ];

        $req = $this->execute($sql, $param);

        if (!$req) {
            return false;
        }

        return $req->fetch(PDO::FETCH_ASSOC);
    }

    // methode pour recuperer les annonces creer par un utilisateur
    function getAnnoncesUtilisateur($idUtilisateur)
    {
        // Rôle : récupérer les annonces créées par un utilisateur
        // Paramètre : $idUtilisateur
        // Retour : tableau des annonces de l'utilisateur

        $sql = "SELECT
            annonce.id,
            annonce.titre,
            annonce.etat,
            annonce.prix_depart,
            annonce.date_heure_fin,
            photo.fichier,
            COALESCE(MAX(enchere.montant), annonce.prix_depart) AS prix_actuel
        FROM annonce
        LEFT JOIN enchere
            ON annonce.id = enchere.annonce
        LEFT JOIN photo
            ON annonce.id = photo.annonce
            AND photo.principale = 1
        WHERE annonce.utilisateur = :utilisateur
        GROUP BY
            annonce.id,
            annonce.titre,
            annonce.etat,
            annonce.prix_depart,
            annonce.date_heure_fin,
            photo.fichier
        ORDER BY annonce.date_heure_fin DESC
    ";

        $param = [
            ":utilisateur" => $idUtilisateur
        ];

        return $this->sqlToArray($sql, $param);
    }

    //methode pour effectuer une recherche
    function rechercher($motCle, $categorie, $etat, $prixMax, $vente)
    {
        // role : rechercher les annonces selon plusieurs criteres
        // parametres :
        //      $motCle : mot recherché dans le titre
        //      $categorie : cateegorie choisie
        //      $etat : etat de l'objet
        //      $prixMax : prix maximum recherché
        // retour : tableau contenant les annonces correspondantes

        $sql = "SELECT
        annonce.id,
        annonce.titre,
        annonce.etat,
        annonce.date_heure_fin,
        annonce.prix_depart,
        photo.fichier,
        COALESCE(MAX(enchere.montant), annonce.prix_depart) AS prix_actuel
    FROM annonce
    LEFT JOIN enchere
        ON annonce.id = enchere.annonce
    LEFT JOIN photo
        ON annonce.id = photo.annonce
        AND photo.principale = 1
    WHERE 1 = 1";

        $param = [];

        if ($vente == "en_cours") {

            $sql .= " AND annonce.date_heure_fin > NOW()";
        }

        if ($vente == "terminee") {

            $sql .= " AND annonce.date_heure_fin <= NOW()";
        }


        // mot-clef
        if ($motCle != "") {
            $sql .= " AND (
            annonce.titre LIKE :mot_cle
            OR annonce.description LIKE :mot_cle)";
            $param[":mot_cle"] = "%" . $motCle . "%";
        }

        // categorie
        if ($categorie != "") {
            $sql .= " AND annonce.categorie = :categorie";
            $param[":categorie"] = $categorie;
        }

        // etat
        if ($etat != "") {
            $sql .= " AND annonce.etat = :etat";
            $param[":etat"] = $etat;
        }

        $sql .= " GROUP BY
            annonce.id,
            annonce.titre,
            annonce.etat,
            annonce.date_heure_fin,
            annonce.prix_depart,
            photo.fichier";

        // prix maximum
        if ($prixMax != "") {
            $sql .= " HAVING COALESCE(MAX(enchere.montant), annonce.prix_depart) <= :prix_max";

            $param[":prix_max"] = $prixMax;
        }

        $sql .= " ORDER BY annonce.date_heure_fin ASC";

        return $this->sqlToArray($sql, $param);
    }
}
