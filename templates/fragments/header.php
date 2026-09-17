<?php
/*
Type de fichier :
Fragment de template.

Analyse du besoin :
Afficher un en-tête et une navigation communs à toutes les pages.

Rôle :
Construire le début du document HTML, afficher le nom de l'application
et adapter les liens de navigation selon l'état de connexion de l'utilisateur.

Parametre :
Aucun paramètre direct.
Le fragment utilise :
- le nom du contrôleur actuellement exécuté ;
- l'état de connexion stocké en session ;
- l'utilisateur actuellement connecté.

Retour attendu :
Produit le début du document HTML ainsi que l'en-tête et la navigation.
*/
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Qui Dit Mieux application de vente d'enchère entre particulier</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <header>
        <div class="conteneur header-contenu">

            <?php
            // identifie le controleur actuellement exécuté
            // afin de mettre en evidence le lien de navigation correspondant.
            $pageActive = basename($_SERVER["PHP_SELF"]);
            ?>


            <h1>Qui Dit Mieux</h1>
            <nav>
                <a href="index.php" class="<?php echo ($pageActive == "index.php") ? "lien-actif" : ""; ?>">Accueil</a>
                <a href="afficher_formulaire_recherche.php">Recherche</a>
                <?php
                // Adapte les liens proposés selon l'état de connexion.
                if (isConnected()) { ?>
                    <a href="afficher_tableau_bord.php">Tableau de bord</a>
                    <a href="afficher_formulaire_vente.php">Proposer une vente</a>
                    <a href="afficher_profil.php">Profil</a>
                    <a href="traiter_deconnexion.php">Deconnexion</a>

                <?php } else { ?>


                    <a href="afficher_formulaire_inscription.php">Inscription</a>
                    <a href="afficher_formulaire_connexion.php">Connexion</a>

                <?php } ?>
            </nav>


        </div>

    </header>
