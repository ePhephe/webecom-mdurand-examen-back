<?php

/**
 * Template : Formulaire de gestion des utilisateurs
 *
 * Paramètres :
 *  htmlFormulaire - Code HTML du formulaire pour gérer le projet
 */

?>
<!-- Main : Contenu principal de la page -->
<main id="page-form-user">
    <div class="container-full margin-top-40px flex justify-center">
        <!-- Div pour le formulaire -->
        <div class="large-8-12 flex align-center direction-column gap">
            <h1>Compte utilisateur</h1>

            <?= $parametres["htmlFormulaire"] ?>
        </div>
    </div>
    <!-- Image de fond pour la page -->
    <div class="image-fond image-fond-projet">

    </div>
</main>
<script src="public/js/form_utilisateur.js" defer></script>

