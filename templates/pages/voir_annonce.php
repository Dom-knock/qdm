<?php
/*
Analyse du besoin :
afficher le détail d'une annonce sélectionnée

Responsable :
le controleur afficher_annonce.php recupere les données

Role :
Produire le HTML de la page de detail d'une annonce

Parametre :
$annonce : tableau contenant les informations de l'annonce

Retour :
affiche le detail de l'annonce
*/
//Pour empecher les erreurs VScode

/** @var array $annonce */
/** @var bool $aDesEncheres */

?>

<!-- En-tête commun du site -->
<?php require "templates/fragments/header.php"; ?>

<main>

    <div class="conteneur">

        <article class="detail-annonce">

            <!-- affiche la photo principale si elle existe -->
            <?php if (!empty($annonce["fichier"])) { ?>

                <img class="image-annonce" src="<?php echo htmlentities($annonce["fichier"]); ?>" alt="<?php echo htmlentities($annonce["titre"]); ?>">

            <?php } else { ?>

                <p>Aucune photo</p>

            <?php } ?>

            <h2><?php echo htmlentities($annonce["titre"]); ?></h2>

            <p>Vendeur : <?php echo htmlentities($annonce["vendeur"]); ?></p>

            <p>Catégorie : <?php echo htmlentities($nomCategorie); ?></p>

            <p>Description : <?php echo nl2br(htmlentities($annonce["description"])); ?></p>

            <p>Etat : <?php echo htmlentities($annonce["etat"]); ?></p>

            <p>Prix de départ : <?php echo number_format($annonce["prix_depart"], 2, ",", " "); ?> €</p>

            <p>Prix actuel : <?php echo number_format($annonce["prix_actuel"], 2, ",", " "); ?> €</p>

            <p>Fin de l'enchère : <?php echo htmlentities($annonce["date_heure_fin"]); ?></p>

            <?php

            // affiche le formulaire d'enchere uniquement si l'utilisateur est connecté,
            // n'est pas le proprietaire et si l'annonce est encore en cours

            if (isConnected()) {

                if ($annonce["utilisateur"] != idConnected()) {

                    if (strtotime($annonce["date_heure_fin"]) > time()) {
            ?>

                        <h3>Enchérir</h3>

                        <form action="traiter_enchere.php" method="POST">

                            <input type="hidden" name="annonce" value="<?php echo $annonce["id"]; ?>">

                            <label for="montant">Votre enchère</label>

                            <input type="number" id="montant" name="montant" step="0.01" min="0.01" required>

                            <button type="submit">Enchérir</button>

                        </form>

            <?php
                    }
                }
            }
            ?>

            <?php

            // affiche l'historique des encheres de l'annonce
            if ($peutVoirHistorique) {
                if (!empty($encheres)) {
            ?>

                    <h3>Historique des enchères</h3>

                    <table>

                        <thead>
                            <tr>
                                <th>Utilisateur</th>
                                <th>Montant</th>
                                <th>Date</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php foreach ($encheres as $enchere) { ?>

                                <tr>
                                    <td>
                                        <?php echo htmlentities($enchere["pseudo"]); ?>
                                    </td>

                                    <td>
                                        <?php echo number_format($enchere["montant"], 2, ",", " "); ?> €
                                    </td>

                                    <td>
                                        <?php echo htmlentities($enchere["date_heure"]); ?>
                                    </td>
                                </tr>

                            <?php } ?>

                        </tbody>

                    </table>

            <?php
                }
            }
            ?>

            <?php

            // on verifie que c'est bien l'auteur de l'annonce

            if (isConnected()) {

                if ($annonce["utilisateur"] == idConnected()) {

                    if (!$aDesEncheres) {
            ?>

                        <a class="bouton" href="afficher_modifier_annonce.php?id=<?php echo $annonce["id"]; ?>">
                            Modifier l'annonce
                        </a>

                        <a class="bouton" href="traiter_supprimer_annonce.php?id=<?php echo $annonce["id"]; ?>"
                            onclick="return confirm('Voulez-vous vraiment supprimer cette annonce ?');">
                            Supprimer l'annonce
                        </a>

                    <?php
                    } else {
                    ?>

                        <p> Cette annonce a reçu une enchère et ne peut plus être modifiée ou supprimée.</p>

            <?php
                    }
                }
            }
            ?>


            <?php

            // confirme a l'utilisateur que son enchere a bien été enregistrée
            if (isset($_GET["enchere"])) {

                if ($_GET["enchere"] == "ok") {
            ?>

                    <script>
                        alert("Votre enchère a bien été prise en compte.");
                    </script>

            <?php
                }
            }
            ?>

            <?php

            // affiche le bouton de suivi pour un utilisateur connecté
            // qui n'est pas le vendeur de l'annonce
            if (isConnected()) {

                if ($annonce["utilisateur"] != idConnected()) {
            ?>

                    <?php if ($estSuivie) { ?>

                        <a class="bouton" href="traiter_suivi.php?id=<?php echo $annonce["id"]; ?>">Ne plus suivre cette annonce
                        </a>

                    <?php } else { ?>

                        <a class="bouton" href="traiter_suivi.php?id=<?php echo $annonce["id"]; ?>"> Suivre cette annonce
                        </a>

                    <?php } ?>

            <?php
                }
            }
            ?>

            <a class="bouton" href="index.php">Retour à l'accueil</a>

        </article>

    </div>

</main>

<!-- Pied de page commun du site -->
<?php require "templates/fragments/footer.php"; ?>
