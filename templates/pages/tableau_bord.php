<?php

// Template :
// tableau_bord.php

// Role :
// afficher le tableau de bord de l'utilisateur connecté

// Parametre :
// $annonces, tableau contenant les annonces de l'utilisateur

// Retour :
// affiche le tableau de bord

?>

<!-- Affiche l'en-tête commun du site.-->
<?php require "templates/fragments/header.php"; ?>

<main>

    <div class="conteneur">

        <h2>Mon tableau de bord</h2>


        <!-- Mes annonces-->

        <h3>Mes annonces</h3>

        <?php if (empty($annonces)) { ?>

            <p>Vous n'avez publié aucune annonce.</p>

        <?php } else { ?>

            <div class="liste-annonces">

                <?php foreach ($annonces as $annonce) { ?>

                    <article class="carte-annonce">

                        <?php if (!empty($annonce["fichier"])) { ?>

                            <img class="image-annonce" src="<?php echo htmlentities($annonce["fichier"]); ?>"
                                alt="<?php echo htmlentities($annonce["titre"]); ?>">

                        <?php } else { ?>

                            <p>Aucune photo</p>

                        <?php } ?>

                        <h4><?php echo htmlentities($annonce["titre"]); ?></h4>

                        <p>Etat : <?php echo htmlentities($annonce["etat"]); ?></p>

                        <p>Prix actuel : <?php echo number_format($annonce["prix_actuel"], 2, ",", " "); ?> €</p>

                        <p>Fin de l'enchère : <?php echo htmlentities($annonce["date_heure_fin"]); ?></p>

                        <a class="bouton" href="afficher_annonce.php?id=<?php echo $annonce["id"]; ?>">Voir</a>

                        <a class="bouton" href="afficher_modifier_annonce.php?id=<?php echo $annonce["id"]; ?>">
                            Modifier
                        </a>

                        <a class="bouton" href="traiter_supprimer_annonce.php?id=<?php echo $annonce["id"]; ?>"
                            onclick="return confirm('Voulez-vous vraiment supprimer cette annonce ?');">
                            Supprimer
                        </a>

                    </article>

                <?php } ?>

            </div>

        <?php } ?>


        <!-- Mes encheres -->

        <h3>Mes enchères</h3>

        <?php if (empty($mesEncheres)) { ?>

            <p>Vous n'avez encore participé à aucune enchère.</p>

        <?php } else { ?>

            <div class="liste-annonces">

                <?php foreach ($mesEncheres as $enchere) { ?>

                    <article class="carte-annonce">

                        <?php if (!empty($enchere["fichier"])) { ?>

                            <img class="image-annonce" src="<?php echo htmlentities($enchere["fichier"]); ?>"
                                alt="<?php echo htmlentities($enchere["titre"]); ?>">

                        <?php } else { ?>

                            <p>Aucune photo</p>

                        <?php } ?>

                        <h4><?php echo htmlentities($enchere["titre"]); ?></h4>

                        <p>Ma meilleure enchère :
                            <?php echo number_format($enchere["meilleure_enchere_utilisateur"], 2, ",", " "); ?> €
                        </p>

                        <p>Prix actuel :
                            <?php echo number_format($enchere["prix_actuel"], 2, ",", " "); ?> €
                        </p>

                        <p>Fin de l'enchère :
                            <?php echo htmlentities($enchere["date_heure_fin"]); ?>
                        </p>

                        <?php
                        if ($enchere["meilleure_enchere_utilisateur"] == $enchere["prix_actuel"]) {
                        ?>
                            <p>Vous êtes actuellement le meilleur enchérisseur.</p>
                        <?php
                        } else {
                        ?>
                            <p>Votre enchère a été dépassée.</p>
                        <?php
                        }
                        ?>

                        <a class="bouton" href="afficher_annonce.php?id=<?php echo $enchere["id"]; ?>">
                            Voir l'annonce
                        </a>

                    </article>

                <?php } ?>

            </div>

        <?php } ?>


        <!-- Mes suivis -->

        <h3>Mes suivis</h3>

        <?php if (empty($mesSuivis)) { ?>

            <p>Vous ne suivez actuellement aucune annonce.</p>

        <?php } else { ?>

            <div class="liste-annonces">

                <?php foreach ($mesSuivis as $suivi) { ?>

                    <article class="carte-annonce">

                        <?php if (!empty($suivi["fichier"])) { ?>

                            <img class="image-annonce"
                                src="<?php echo htmlentities($suivi["fichier"]); ?>"
                                alt="<?php echo htmlentities($suivi["titre"]); ?>">

                        <?php } else { ?>

                            <p>Aucune photo</p>

                        <?php } ?>

                        <h4><?php echo htmlentities($suivi["titre"]); ?></h4>

                        <p>Etat : <?php echo htmlentities($suivi["etat"]); ?></p>

                        <p>Prix actuel :
                            <?php echo number_format($suivi["prix_actuel"], 2, ",", " "); ?> €
                        </p>

                        <p>Nombre d'enchères :
                            <?php echo htmlentities($suivi["nombre_encheres"]); ?>
                        </p>

                        <p>Fin de l'enchère :
                            <?php echo htmlentities($suivi["date_heure_fin"]); ?>
                        </p>

                        <a class="bouton" href="afficher_annonce.php?id=<?php echo $suivi["id"]; ?>">
                            Voir l'annonce
                        </a>

                    </article>

                <?php } ?>

            </div>

        <?php } ?>


        <!-- Mes encheres remportées -->

        <section class="encheres-remportees">

            <h3>Mes enchères remportées</h3>

            <?php if (empty($encheresRemportees)) { ?>

                <p>Vous n'avez remporté aucune enchère.</p>

            <?php } else { ?>

                <div class="liste-annonces">

                    <?php foreach ($encheresRemportees as $enchereRemportee) { ?>

                        <article class="carte-annonce">

                            <?php if (!empty($enchereRemportee["fichier"])) { ?>

                                <img class="image-annonce"
                                    src="<?php echo htmlentities($enchereRemportee["fichier"]); ?>"
                                    alt="<?php echo htmlentities($enchereRemportee["titre"]); ?>">

                            <?php } else { ?>

                                <p>Aucune photo</p>

                            <?php } ?>

                            <h4><?php echo htmlentities($enchereRemportee["titre"]); ?></h4>

                            <p>Prix final :
                                <?php echo number_format($enchereRemportee["prix_final"], 2, ",", " "); ?> €
                            </p>

                            <p>Vente terminée le :
                                <?php echo htmlentities($enchereRemportee["date_heure_fin"]); ?>
                            </p>

                            <p><strong>Vous avez remporté cette enchère.</strong></p>

                            <a class="bouton" href="afficher_annonce.php?id=<?php echo $enchereRemportee["id"]; ?>">
                                Voir l'annonce
                            </a>

                        </article>

                    <?php } ?>

                </div>

            <?php } ?>

        </section>

    </div>

</main>

<?php require "templates/fragments/footer.php"; ?>
