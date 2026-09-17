<?php
/*
Analyse du besoin :
Afficher la page d'accueil et les annonces encours

Responsable :
Le controleur index.php recupere les annonces

Role :
Produire le HTML de la page d'accueil

Parametre :
$annonces : tableau contenant les annonces en cours

Retour :
Affiche le HTML de la page d'accueil
*/
?>

<!-- Affiche l'en-tête commun du site -->
<?php require "templates/fragments/header.php"; ?>

<main>

    <div class="conteneur">

        <h2>Annonces en cours</h2>

        <?php

        if (isset($_GET["suppression"])) {

            if ($_GET["suppression"] == "ok") {
        ?>

                <p class="message-succes">L'annonce a bien été supprimée.</p>

        <?php
            }
        }
        ?>

        <section class="liste-annonces">
            <!-- on verifie si aucune annonce n'est disponible -->
            <?php if (empty($annonces)) { ?>

                <p>Aucune annonce en cours pour le moment.</p>

            <?php } else { ?>
                <!-- on parcourt les annonces recuperé par le controleur -->
                <?php foreach ($annonces as $annonce) { ?>

                    <article class="carte-annonce">
                        <!-- on affiche la photo principale si elle existe -->
                        <?php if (!empty($annonce["fichier"])) { ?>

                            <img class="image-annonce" src="<?php echo htmlentities($annonce["fichier"]); ?>" alt="<?php echo htmlentities($annonce["titre"]); ?>">

                        <?php } else { ?>

                            <p>Aucune photo</p>

                        <?php } ?>
                        <!-- les infos principales de l'annonce -->
                        <h3><?php echo htmlentities($annonce["titre"]); ?></h3>


                        <p>État : <?php echo htmlentities($annonce["etat"]); ?></p>

                        <p>Prix actuel : <?php echo number_format($annonce["prix_actuel"], 2, ",", " "); ?> €</p>

                        <p>Fin de l'enchère : <?php echo htmlentities($annonce["date_heure_fin"]); ?></p>

                        <a class="bouton" href="afficher_annonce.php?id=<?php echo $annonce["id"]; ?>">Voir l'annonce</a>

                    </article>

                <?php } ?>

            <?php } ?>

        </section>

    </div>

</main>

<!-- Pied de page commun du site -->
<?php require "templates/fragments/footer.php"; ?>
