<?php
/*

Type de fichier :
Templat.

Analyse du besoin :
Présenter le formulaire de connexion et les informations envoyées par le contrôleur

Role :
Présenter le formulaire de connexion envoyés par le contrôleur

Données reçues :
$message
Contient un message d'erreur si la connexion a échoué
$identifiant
Contient le pseudo ou l'adresse email

Retour attendu :
Produit le HTML de la page de connexion
*/
?>

<!-- Affiche l'en-tête commun du site.-->
<?php require "templates/fragments/header.php"; ?>

<main>
    <div class="conteneur">
        <section class="bloc-formulaire">
            <h2>Connexion</h2>

            <!-- si le controleur a envoyé un message,
             je l'affiche en protegeant son contenu avec htmlentities.-->
            <?php if (!empty($message)) { ?>
                <p class="message-erreur"><?php echo htmlentities($message); ?></p>
            <?php }
            ?>

            <p>Veuillez entrer vos identifiants</p>

            <!--formulaire de connexion -->
            <form action="traiter_connexion.php" method="post">

                <!--champ permettant de se connecter avec un identifiant
            soit avec un pseudo ou avec un email-->
                <div class="champ-formulaire">

                    <label for="identifiant">Veuillez entrer votre identifiant, pseudo ou email</label>
                    <input type="text" id="identifiant" name="identifiant" value="<?php echo htmlentities($identifiant ?? ""); ?>"
                        required>
                </div>

                <!-- champ permettant de saisir le mot de passe. -->
                <div class="champ-formulaire">
                    <label for="password">Mot de passe</label>
                    <input type="password" name="password" id="password" required>
                </div>

                <!-- bouton envoyant le formulaire au contrôleur. -->
                <button type="submit" class="bouton">Se connecter</button>
            </form>
        </section>
    </div>
</main>

<!--affiche le pied de page commun du site.-->
<?php require "templates/fragments/footer.php"; ?>
