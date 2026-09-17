<?php

// Classse _ model : classe générique pour gérer un objet du MCD


class _model
{


    // Attributs : décrire le MCD
    protected $table = "";
    protected $fields = [];
    protected $links = [];

    // Attributs : informations récupérées dans la BDD
    protected $values = [];        // Ce sera un tableau indexé par les noms des champs, dont la valeur est la valeur du champ, sauf l'id
    protected $id = 0;              // ce sera l'id effectif de l'objet


    /* =========================
   Constructeur
   ========================= */

    function __construct($id = null)
    {
        // Rôle : charger une ligne de la bse de données si on le veut (se déclenche à l'instanciation d'un objet)
        // Paramètres :
        //      $id (facultatif) : id de la tâce à charger
        // Retour : néant

        // Si on a donné un $id
        if (! is_null($id)) {
            // On charge cet objet
            $this->load($id);
        }
    }

    /* =========================
   État de l'objet
   ========================= */

    function is()
    {
        // Rôle : dire si la tâche est dans la BDD
        // Parmètres : néant
        // Retour : true si existe, false sinon

        return !empty($this->id);
    }

    /* =========================
   Getters
   ========================= */

    // Getters
    function get($name)
    {
        // Rôle : récupérer la valeur d'un champ donné
        // Paramètres :
        //      $name : nom du champ à récupérer
        // Retour : la valeur ou null

        // Cas particulier : l'id n'est pas dans $this->values
        if ($name == "id") {
            return $this->id;
        }

        // Est-ce un champ qui existe ?
        if (!in_array($name, $this->fields)) {
            return null;
        }

        // Si le champ existe et a une valeur
        if (isset($this->values[$name])) {
            return $this->values[$name];
        }

        return null;
    }

    function id()
    {
        // Rôle : récupérer la valeur de la clé primaire
        // Paramètres : néant
        // Rerour : valeur de l'id ou 0 si objet non chargé

        if ($this->is()) return $this->id;
        else return 0;
    }

    /* =========================
   Setters
   ========================= */


    // setters
    function set($name, $valeur)
    {
        // Rôle : modifier la valeur d'un attribut
        // Paramètres :
        //      $name : nom du champ à modifier
        //      $valeur : valeur à affecter à ce champ
        // Return : true si ok, false sinon

        // Si l'attribut n'existe pas : on retourne false
        if (! in_array($name, $this->fields)) {
            // Le champ n'existe pas
            return false;
        }

        // IL existe : on change sa valeur
        $this->values[$name] = $valeur;
        return true;
    }

    /* =========================
   Synchronisation BDD
   ========================= */



    // Méthodes de "synchronisation" avec la BDD
    function load($id)
    {
        // Rôle : charger une ligne de la BDD dans l'objet courant
        // Paramètres :
        //      $id : id à charger
        // Retour : true si réussi, false sinon

        // Construire la requête SQL et ses paramètres :xxxx
        $sql = "SELECT " . $this->listFieldsSelect() . " FROM `$this->table` WHERE id = :id ";
        $paramValues = [":id" => $id];

        // Préparer / excéuter la requête
        $req = $this->execute($sql, $paramValues);

        // Si échec :
        if ($req === false) {
            return false;
        }

        // exploiter le résultat
        // récupérer les lignes retournées par la requête
        $lignes = $req->fetchAll(PDO::FETCH_ASSOC);

        // Si on n'a pas de lignes
        if (empty($lignes)) return false;

        // On a au moins une ligne : on  transfere le 1er elet dans les attribut de l'objet courant
        return $this->loadFromTab($lignes[0]);
    }

    function insert()
    {
        // Rôle : créer la ligne correspondant a l'objet courant dans la BDD (on devra aussi mettre à jour l'id dans l'objet)
        // Paramètres : néant
        // Retour : true si réussi, false sinon


        // Construire la requête SQL et ses paramètres :xxxx
        $sql = "INSERT INTO `$this->table` SET " . $this->listFieldsForSet();
        $param = [];
        foreach ($this->fields as $nomAttribut) {
            if (isset($this->values[$nomAttribut])) $param[":$nomAttribut"] = $this->values[$nomAttribut];
            else $param[":$nomAttribut"] = null;
        }

        // Préparer / excéuter la requête
        $req = $this->execute($sql, $param);

        // Si échec :
        if ($req === false) {
            return false;
        }

        // on a réusi l'insertion : on met à jour l'id de l'objet courant et on retourne true
        global $bdd;
        $this->id = $bdd->lastInsertId();
        return true;
    }

    function update()
    {
        // Rôle : mettre à jour la ligne correspondant a l'objet courant dans la BDD
        // Paramètres : néant
        // Retour : true si réussi, false sinon


        // Construire la requête SQL et ses paramètres :xxxx
        $sql = "UPDATE `$this->table` SET " . $this->listFieldsForSet() . " WHERE id = :id";
        $param = [":id" => $this->id];
        foreach ($this->fields as $nomAttribut) {
            if (isset($this->values[$nomAttribut])) $param[":$nomAttribut"] = $this->values[$nomAttribut];
            else $param[":$nomAttribut"] = null;
        }

        // Préparer / excéuter la requête
        $req = $this->execute($sql, $param);

        // Si échec :
        if ($req === false) {
            return false;
        }

        // on a réusi la modification :
        return true;
    }

    function delete()
    {
        // Rôle : supprimer la ligne correspondant a l'objet courant de la BDD
        // (puis remet l'id à zéro dans cet objet, remettre à null tous les champs pere et mere correspondant à l'id courant, des enfants de l'objet courant)
        // Paramètres : néant
        // Retour : true si réussi, false sinon

        // Etape 1) Supprime l'enregistrement correpondant à l'id courant dans la BDD
        // Construire la requête SQL et ses paramètres :xxxx
        $sql = "DELETE FROM `$this->table`  WHERE id = :id";
        $param = [":id" => $this->id];

        // Préparer / excéuter la requête
        $req = $this->execute($sql, $param);

        // Si échec :
        if ($req === false) {
            return false;
        }

        // Etape 3 : remettre mon id à zéro pour indiquer que l'objet n'est plus dans la BDD
        $this->id = 0;
        return true;
    }


    /* =========================
   Methodes utiles
   ========================= */

    function listFieldsSelect()
    {
        // Rôle : générer la liste des champs de la table pour une requête SQL de type SELECT
        // Paramètres : néant
        // Retour : le texte prêt pour SQL
        $liste = "`id`";
        // Pour chaque champ, ajoute , `nomduchamp`
        foreach ($this->fields as $nomChamp) {
            $liste .= ",`$nomChamp`";
        }
        return $liste;
    }



    function listFieldsForSet()
    {
        // Rôle : générer la liste des champs de la table pour une requête SQL de type UPDATE ou INSERT
        //              `nomChamp1` = :nomChamp1, `nomChamp2` = :nomChamp2, ...
        // Paramètres : néant
        // Retour : le texte prêt pour SQL

        $liste = [];  // On va construire un tableau que l'on implosera en texte à la fin

        foreach ($this->fields as $nomChamp) {
            $liste[] = "`$nomChamp` = :$nomChamp";
        }
        return implode(", ", $liste);
    }

    /* =========================
   Exécution SQL
   ========================= */

    function execute($sql, $param = [])
    {
        // Rôle : Préparer et exécuter une requête dans la BDD, et retourner l'objet requête préparée
        // Paramètres :
        //      $sql : texte de la requête sql (avec des :xxxx)
        //      $param : tableau donnant les valeurs de :xxxx
        // retour : objet requête préparée (objet donné par $bdd->prepare()), ou false si échec

        // récupération dde la variable globale dans laquelle on a ouver la BDD
        global $bdd;

        // préparer une requête (et récupérer une requête préparée)
        $req = $bdd->prepare($sql);
        // Car d'erreur :
        if ($req === false) {
            // Dans la phase de debug, on peut afficher $sql
            return false;
        }

        // exécuter, en lui donnant la valeur
        if (! $req->execute($param)) {
            // Dans la phase de debug, on peut afficher $sql et $param
            return false;
        }

        // Cela s'est bien passé : on retourne la requête prépare et exécutée
        return $req;
    }

    /* =========================
   Chargement des attributs
   ========================= */

    function loadFromTab($tab)
    {
        // Rôle : valoriser les attributs (id, nom, ....) à partir des éléments d'un tableau
        // Paramètres :
        //      $tab : tableau indexé dont les clés sont des noms d'attributs (de colones de la table tache)
        // Retour : true si réussi, false sinon

        // Pour chacun des attribut nom, etc....
        foreach ($this->fields as $nomAttribut) {
            // Si l'attribut est présentcdabsle tableau fourni
            if (isset($tab[$nomAttribut])) {
                // On le copie dans la valeur de l'attribut
                $this->values[$nomAttribut] = $tab[$nomAttribut];
            }
        }

        // On gère l'id
        if (isset($tab["id"])) $this->id = $tab["id"];

        return true;
    }

    /* =========================
   Résultats SQL vers objets
   ========================= */

    function sqlToTab($sql, $param = [])
    {
        // Rôle : à partir d'une requête sql SELECT, construit une liste d'objet tache (contenant toutes les taches extraites par la reqête SQL)
        // Paramètres elements de la requêtes SQL
        //      $sql : texte SQL de la reqêtes avec des paramères :xxxx
        //      $param : tableau donnant les valeurs des :xxxx
        // Retour : tableau d'objets de la classe personne, indexé par l'id des personnes

        // Exécuter la requête
        $req = $this->execute($sql, $param);

        $lignes = $req->fetchAll(PDO::FETCH_ASSOC);
        $resultat = [];     // Pour construire le résultat

        // Pour chaque ligne de la table
        foreach ($lignes as $ligne) {
            // On crée un objet personne
            $objet = new static();
            // On le charge avec cette ligne
            $objet->loadFromTab($ligne);
            // On l'ajoute dans le tableau résultat
            $resultat[$objet->id()] = $objet;
        }

        return $resultat;
    }



    /* =========================
   Listes
   ========================= */

    function getAll()
    {
        // rôle : récupérer tous les enregistrements de la table
        // paramètres : néant
        // retour : tableau d'objets indexés par leur id

        $sql = "SELECT " . $this->listFieldsSelect() . " FROM `$this->table`";

        return $this->sqlToTab($sql);
    }

    function loadBy($champ, $valeur)
    {
        // rôle : charger un objet depuis une colonne donnée
        // paramètres :
        //      $champ : nom du champ utilisé pour chercher
        //      $valeur : valeur recherchée
        // retour : true si trouvé, false sinon

        // Vérifier que le champ demandé existe bien dans la liste des champs autorisés
        if (!in_array($champ, $this->fields)) {
            return false;
        }

        $sql = "SELECT " . $this->listFieldsSelect() . " FROM `" . $this->table . "` WHERE `" . $champ . "` = :valeur";

        $param = [
            ":valeur" => $valeur
        ];

        $req = $this->execute($sql, $param);

        if (!$req) {
            return false;
        }

        $ligne = $req->fetch(PDO::FETCH_ASSOC);

        if (!$ligne) {
            return false;
        }

        $this->loadFromTab($ligne);

        return true;
    }

    /* =========================
   Résultats SQL vers tableaux
   ========================= */

    function sqlToArray($sql, $param = [])
    {
        // Rôle : exécuter une requête SQL et retourner les lignes sous forme de tableau
        // Paramètres :
        //      $sql : requête SQL
        //      $param : paramètres de la requête
        // Retour : tableau associatif

        $req = $this->execute($sql, $param);

        // Si la requête échoue
        if (!$req) {
            return [];
        }

        return $req->fetchAll(PDO::FETCH_ASSOC);
    }
}
