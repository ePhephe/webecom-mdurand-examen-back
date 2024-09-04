<?php

/**
 * Template : Formulaire de gestion des commandes
 *
 * Paramètres :
 *  htmlFormulaire - Code HTML du formulaire pour gérer la commande
 *  action - Facultatif - Action réalisée
 *  commande - Facultatif - Objet de la commande
 */

?>

<!-- Main : Contenu principal de la page -->
<main id="page-new-produit">
    <div class="container-full margin-top-40px flex justify-center">
        <!-- Div pour le formulaire -->
        <div class="large-6-12 medium-6-8 small-4-4 flex align-center direction-column gap">
            <?php if($parametres["action"] === "create") { ?>
                <h1>Création d'une nouvelle commande</h1>
            <?php } elseif($parametres["action"] === "update") { ?>
                <h1>Modification de la commande <?= $parametres["produit"]->getValue("c_num_cde") ?></h1>
            <?php } else { ?>
                <h1>Gestion de commande</h1>
            <?php } ?>

            <?= $parametres["htmlFormulaire"] ?>
        </div>
    </div>
</main>
<script>
    let idCommande = <?= ($parametres["commande"]->is()) ? $parametres["commande"]->id() : 0 ?>;
</script>
<script src="public/js/form_commande.js" defer></script>

