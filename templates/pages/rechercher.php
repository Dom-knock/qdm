<?php

// Template :
// rechercher.php

// Role :
// afficher le formulaire de recherche d'annonces

// Parametres :
//      $categories : tableau des catégories récupérées depuis l'API

// Retour :
// affiche le formulaire de recherche

/** @var array $categories */
?>

<!-- En-tête commun du site -->
<?php require "templates/fragments/header.php"; ?>

<main>

    <div class="conteneur">

        <h2>Rechercher une annonce</h2>

        <form action="afficher_formulaire_recherche.php" method="GET">

            <label for="mot_cle">Mot-clé</label>

            <input type="text" id="mot_cle" name="mot_cle"
                value="<?php echo htmlentities($motCle); ?>">


            <label for="categorie">Catégorie</label>

            <select id="categorie" name="categorie">

                <option value="">Toutes les catégories</option>

                <?php foreach ($categories as $idCategorie => $nomCategorie) { ?>

                    <option
                        value="<?php echo htmlentities($idCategorie); ?>"
                        <?php if ($categorie == $idCategorie) {
                            echo "selected";
                        } ?>>
                        <?php echo htmlentities($nomCategorie); ?>
                    </option>

                <?php } ?>

            </select>


            <fieldset>

                <legend>Etat de l'objet</legend>

                <label>
                    <input type="radio" name="etat" value=""
                        <?php if ($etat == "") {
                            echo "checked";
                        } ?>>
                    Tous
                </label>

                <label>
                    <input type="radio" name="etat" value="neuf"
                        <?php if ($etat == "neuf") {
                            echo "checked";
                        } ?>>
                    Neuf
                </label>

                <label>
                    <input type="radio" name="etat" value="tres bon etat"
                        <?php if ($etat == "tres bon etat") {
                            echo "checked";
                        } ?>>
                    Très bon état
                </label>

                <label>
                    <input type="radio" name="etat" value="bon etat"
                        <?php if ($etat == "bon etat") {
                            echo "checked";
                        } ?>>
                    Bon état
                </label>

                <label>
                    <input type="radio" name="etat" value="etat correct"
                        <?php if ($etat == "etat correct") {
                            echo "checked";
                        } ?>>
                    État correct
                </label>

            </fieldset>

            <fieldset>

                <legend>État de la vente</legend>

                <label>
                    <input type="radio" name="vente" value=""
                        <?php if ($vente == "") {
                            echo "checked";
                        } ?>>
                    Toutes
                </label>

                <label>
                    <input type="radio" name="vente" value="en_cours"
                        <?php if ($vente == "en_cours") {
                            echo "checked";
                        } ?>>
                    En cours
                </label>

                <label>
                    <input type="radio" name="vente" value="terminee"
                        <?php if ($vente == "terminee") {
                            echo "checked";
                        } ?>>
                    Terminées
                </label>

            </fieldset>


            <label for="prix_max">Prix maximum</label>

            <input type="number" id="prix_max" name="prix_max" step="0.01" min="0"
                value="<?php echo htmlentities($prixMax); ?>">


            <button type="submit">Rechercher</button>

        </form>


        <?php if (isset($_GET["mot_cle"]) || isset($_GET["categorie"]) || isset($_GET["etat"]) || isset($_GET["prix_max"])) { ?>

            <h3>Resultats de la recherche</h3>

            <?php if (empty($resultats)) { ?>

                <p>Aucune annonce ne correspond à votre recherche.</p>

            <?php } else { ?>

                <div class="liste-annonces">

                    <?php foreach ($resultats as $annonce) { ?>

                        <article class="annonce">

                            <?php if (!empty($annonce["fichier"])) { ?>

                                <img class="image-annonce" src="<?php echo htmlentities($annonce["fichier"]); ?>" alt="<?php echo htmlentities($annonce["titre"]); ?>">

                            <?php } else { ?>

                                <p>Aucune photo</p>

                            <?php } ?>


                            <h4><?php echo htmlentities($annonce["titre"]); ?></h4>

                            <p>Etat : <?php echo htmlentities($annonce["etat"]); ?></p>

                            <p>Prix actuel : <?php echo number_format($annonce["prix_actuel"], 2, ",", " "); ?> €</p>

                            <p>Fin de l'enchère : <?php echo htmlentities($annonce["date_heure_fin"]); ?></p>

                            <a href="afficher_annonce.php?id=<?php echo $annonce["id"]; ?>">Voir l'annonce</a>

                        </article>

                    <?php } ?>

                </div>

            <?php } ?>

        <?php } ?>

    </div>

</main>


<!-- Pied de page commun du site -->
<?php require "templates/fragments/footer.php"; ?>
