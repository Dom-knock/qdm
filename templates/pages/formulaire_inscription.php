<?php
/*
Type de fichier :
Template.

Analyse du besoin :
Afficher le formulaire de creation de compte utilisateur

Role :
Presenter les champs necessaires à l'inscription et afficher un eventuel message
transmis par le controleur

Parametre :
-$message :
message d'information ou d'erreur à afficher si nécessaire
-$peudo :
Pseudo saisie precedement
-$email :
Email saisie precedement

Retour attendu :
Produit le HTML du formulaire d'inscription.
*/
?>

<!-- Affiche l'en-tête commun du site.-->
<?php require_once "templates/fragments/header.php"; ?>

<main>
    <section class="formulaire">
        <h2>Créer un compte</h2>

        <!-- affiche un éventuel message préparé par le controleur-->
        <?php if (!empty($message)) { ?>
            <p class="message-formulaire"><?php echo htmlentities($message); ?></p>
        <?php } ?>

        <!--les données saisies sont envoyées au controleur traiter_inscription.php,
        qui sera responsable de valider et enregistrer le nouvel utilisateur-->
        <form action="traiter_inscription.php" method="post">

            <div class="champ">
                <label for="pseudo">Pseudo</label>
                <input type="text" id="pseudo" name="pseudo" value="<?php echo htmlentities($pseudo ?? ""); ?>" required>
            </div>

            <div class="champ">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlentities($email ?? ""); ?>" required>
            </div>

            <div class="champ">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="champ">
                <label for="confirmation-password">Confirmer le mot de passe</label>
                <input type="password" id="confirmation-password" name="confirmationPassword" required>
            </div>

            <button type="submit" class="btn">S'inscrire</button>

        </form>

    </section>
</main>

<?php
// Intègre le fragment de pied de page commun
require_once "templates/fragments/footer.php"; ?>
