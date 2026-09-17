<?php

// Template :
//modifier une annonce

// Role :
//afficher le formulaire prérempli avec les informations de l'annonce
// Parametres :
//      $annonce : objet annonce chargé
//      $categories : tableau des categories recuperées depuis l'API
// Retour : affiche le formulaire HTML de modification

/** @var array $categories */

?>

<!-- En-tete commun du site -->
<?php require "templates/fragments/header.php"; ?>

<main>

    <div class="conteneur">

        <h2>Modifier l'annonce</h2>

        <!-- tous les champs obligatoires doivent etre remplis -->
        <?php
        if (isset($message)) {
            echo "<p>" . htmlentities($message) . "</p>";
        }
        ?>

        <form action="traiter_modifier_annonce.php" method="POST" enctype="multipart/form-data">

            <!-- permet au controleur de traitement de savoir quelle annonce modifier -->
            <input type="hidden" name="id" value="<?php echo htmlentities($annonce->id()); ?>">


            <label for="titre">Titre</label>

            <input type="text" id="titre" name="titre" value="<?php echo htmlentities($annonce->get("titre")); ?>"
                required>


            <label for="categorie">Catégorie</label>

            <select id="categorie" name="categorie" required>

                <option value="">Choisir une catégorie</option>

                <?php foreach ($categories as $idCategorie => $nomCategorie) { ?>

                    <option
                        value="<?php echo htmlentities($idCategorie); ?>"

                        <?php
                        if ($annonce->get("categorie") == $idCategorie) {
                            echo "selected";
                        }
                        ?>>
                        <?php echo htmlentities($nomCategorie); ?>
                    </option>

                <?php } ?>

            </select>


            <label for="description">Description</label>

            <textarea
                id="description"
                name="description"
                required><?php echo htmlentities($annonce->get("description")); ?></textarea>


            <fieldset>

                <legend>État de l'objet</legend>

                <label>
                    <input type="radio" name="etat" value="neuf"
                        <?php
                        if ($annonce->get("etat") == "neuf") {
                            echo "checked";
                        }
                        ?>>
                    Neuf
                </label>

                <label>
                    <input type="radio" name="etat" value="tres bon état"
                        <?php
                        if ($annonce->get("etat") == "tres bon état") {
                            echo "checked";
                        }
                        ?>>
                    Tres bon état
                </label>

                <label>
                    <input type="radio" name="etat" value="bon état"
                        <?php
                        if ($annonce->get("etat") == "bon état") {
                            echo "checked";
                        }
                        ?>>
                    Bon état
                </label>

                <label>
                    <input type="radio" name="etat" value="état correct"
                        <?php
                        if ($annonce->get("etat") == "état correct") {
                            echo "checked";
                        }
                        ?>>
                    État correct
                </label>

            </fieldset>


            <label for="prix_depart">Prix de départ</label>

            <input type="number" id="prix_depart" name="prix_depart" step="0.01" min="0.01"
                value="<?php echo htmlentities($annonce->get("prix_depart")); ?>"
                required>
            <?php

            // separe le DATETIME en date et heure pour remplir les deux champs du formulaire
            $dateHeureFin = $annonce->get("date_heure_fin");

            $dateFin = date("Y-m-d", strtotime($dateHeureFin));
            $heureFin = date("H:i", strtotime($dateHeureFin));

            ?>


            <label for="date_fin">Date de fin</label>

            <input type="date" id="date_fin" name="date_fin" value="<?php echo htmlentities($dateFin); ?>" required>

            <label for="heure_fin">Heure de fin</label>

            <input type="time" id="heure_fin" name="heure_fin" value="<?php echo htmlentities($heureFin); ?>" required>

            <label for="photo">Choisir une nouvelle photo</label>

            <input type="file" id="photo" name="photo" accept="image/*">

            <button type="submit">Valider les modifications</button>

        </form>

    </div>

</main>

<!-- Pied de page commun du site -->
<?php require "templates/fragments/footer.php"; ?>
