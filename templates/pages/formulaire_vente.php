<?php
/*
Analyse du besoin :
afficher le formulaire permettant à un utilisateur connecté
de créer une nouvelle annonce

Responsable :
le controleur afficher_formulaire_vente.php

Role :
produire le HTML du formulaire de creation d'annonce

Parametres :
aucun

Retour :
affiche le formulaire de vente

*/
//Pour empecher les erreurs VScode

/** @var array $categories */
?>

<!-- En-tête commun du site -->
<?php require "templates/fragments/header.php"; ?>

<main>

    <div class="conteneur">

        <h2>Créer une annonce</h2>

        <!-- formulaire de creation d'une annonce -->
        <form class="formulaire" action="traiter_vente.php" method="POST" enctype="multipart/form-data">

            <label for="titre">Titre</label>
            <input type="text" id="titre" name="titre" required>

            <label for="categorie">Catégorie</label>

            <select id="categorie" name="categorie" required>
                <option value="">Choisir une catégorie</option>
                <!-- affiche les categories recuperées depuis l'API -->
                <?php foreach ($categories as $idCategorie => $nomCategorie) { ?>

                    <option value="<?php echo htmlentities($idCategorie); ?>">
                        <?php echo htmlentities($nomCategorie); ?>
                    </option>

                <?php } ?>
            </select>

            <label for="description">Description</label>
            <textarea id="description" name="description" required></textarea>

            <fieldset>

                <legend>État de l'objet</legend>

                <label><input type="radio" name="etat" value="neuf" required>Neuf</label>
                <label><input type="radio" name="etat" value="tres bon etat"> Tres bon état</label>
                <label><input type="radio" name="etat" value="etat correct"> etat correct</label>

            </fieldset>

            <label for="prix_depart">Prix de départ</label>
            <input type="number" id="prix_depart" name="prix_depart" step="0.01" min="0" required>

            <label for="date_fin">Date de fin</label>
            <input type="date" id="date_fin" name="date_fin" required>

            <label for="heure_fin">Heure de fin</label>
            <input type="time" id="heure_fin" name="heure_fin" required>

            <!-- selection de plusieurs photos -->
            <label for="photos">Choisir des photos</label>
            <input type="file" id="photo" name="photo" accept="image/*">

            <fieldset>

                <legend>Photo principale</legend>

                <p>Le choix de la photo principale sera proposé après la sélection des photos.</p>

            </fieldset>

            <button class="bouton" type="submit">Valider</button>

        </form>

    </div>

</main>

<!-- Pied de page commun du site -->
<?php require "templates/fragments/footer.php"; ?>
