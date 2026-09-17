<?php
/*

Analyse du besoin :
Représenter une photo
Responsable :
Classe modèle photo
Role :
Manipuler les données de la table photo grace aux méthodes héritées de _model
Retour :
Cette classe ne produit aucun affichage
Elle permet de créer, charger, modifier et supprimer une photo

*/

//charge la classe _model
require_once "core/_model.php";
class photo extends _model
{
    //Cette classe herite de _model
    //$table    -->indique le nom de la table
    //$fields   -->indique les champs simple de la table
    //$links    -->indique les liens vers d'autre table

    protected $table = "photo";
    protected $fields = ["fichier", "principale", "annonce"];
    protected $links = ["annonce" => "annonce"];

    //methode pour stocker une photo
    // role : stocker une photo envoyée avec une annonce
    // parametres :
    //      $tempFile : chemin du fichier temporaire
    //      $nom : nom d'origine du fichier
    // retour : true si le stockage réussit, false sinon

    function stocker($tempFile, $nom)
    {

        // verifie que le fichier temporaire existe
        if (!file_exists($tempFile)) {
            return false;
        }

        //recupère l'extension du fichier d'origine
        $extension = pathinfo($nom, PATHINFO_EXTENSION);

        // crée un nom unique pour éviter les doublons
        $nomFichier = uniqid() . "." . $extension;

        // dossier dans lequel seront stockées les photos
        $dossier = "uploads/photos";

        // crée le dossier s'il n'existe pas
        if (!is_dir($dossier)) {
            mkdir($dossier, 0770, true);
        }

        // chemin complet du nouveau fichier
        $chemin = $dossier . "/" . $nomFichier;

        // deplace le fichier temporaire vers son emplacement définitif
        if (!move_uploaded_file($tempFile, $chemin)) {
            return false;
        }

        // stocke le chemin dans l'objet photo
        $this->set("fichier", $chemin);

        return true;
    }
}
