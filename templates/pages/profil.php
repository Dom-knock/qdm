<?php

// Template : profil.php

// Role :
// afficher les informations du profil utilisateur

// Parametre :
// $utilisateur

// Retour :
// affiche le profil

?>

<!-- Affiche l'en-tête commun du site -->
<?php require "templates/fragments/header.php"; ?>

<main>

    <div class="conteneur">

        <h2>Mon profil</h2>

        <p>
            <strong>Pseudo :</strong>
            <?php echo htmlentities($utilisateur->get("pseudo")); ?>
        </p>

        <p>
            <strong>Email :</strong>
            <?php echo htmlentities($utilisateur->get("email")); ?>
        </p>

        <a href="afficher_formulaire_modifier_profil.php">Modifier mon profil</a>

    </div>

</main>

<!-- Pied de page commun du site -->
<?php require "templates/fragments/footer.php"; ?>
