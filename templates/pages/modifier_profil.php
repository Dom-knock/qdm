<?php

// Template : modifier_profil.php

// Role :
// afficher le formulaire de modification du profil

// Parametre :
// $utilisateur

// Retour :
// affiche le formulaire prérempli

?>

<!-- En-tête commun du site -->
<?php require "templates/fragments/header.php"; ?>

<main>

    <div class="conteneur">

        <h2>Modifier mon profil</h2>

        <!-- gestion des erreurs-->
        <?php if (isset($_GET["erreur"])) { ?>

            <?php if ($_GET["erreur"] == "pseudo") { ?>
                <p>Ce pseudo est deja utilisé.</p>
            <?php } ?>

            <?php if ($_GET["erreur"] == "email_existe") { ?>
                <p>Cet email est deja utilisé.</p>
            <?php } ?>

            <?php if ($_GET["erreur"] == "email") { ?>
                <p>L'adresse email n'est pas valide.</p>
            <?php } ?>

            <?php if ($_GET["erreur"] == "champs") { ?>
                <p>Le pseudo et l'email sont obligatoires.</p>
            <?php } ?>

            <?php if ($_GET["erreur"] == "modification") { ?>
                <p>Une erreur est survenue pendant la modification.</p>
            <?php } ?>

        <?php } ?>

        <!-- formulaire de modification -->
        <form action="traiter_modifier_profil.php" method="POST">

            <label for="pseudo">Nouveau pseudo</label>

            <input type="text" id="pseudo" name="pseudo"
                value="<?php echo htmlentities($utilisateur->get("pseudo")); ?>"
                required>


            <label for="email">Nouvel email</label>

            <input type="email" id="email" name="email"
                value="<?php echo htmlentities($utilisateur->get("email")); ?>"
                required>


            <label for="password">Nouveau mot de passe</label>

            <input type="password" id="password" name="password">

            <p>Laissez le champ vide si vous ne souhaitez pas modifier votre mot de passe.</p>


            <button type="submit">Valider</button>

        </form>

    </div>

</main>

<!-- Pied de page commun du site -->
<?php require "templates/fragments/footer.php"; ?>
