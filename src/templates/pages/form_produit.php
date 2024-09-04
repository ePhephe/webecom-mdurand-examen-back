<?php

/**
 * Template : Formulaire de gestion des produits
 *
 * Paramètres :
 *  htmlFormulaire - Code HTML du formulaire pour gérer le produit
 *  action - Facultatif - Action réalisée
 *  produit - Facultatif - Objet du produit
 */

?>

<!-- Main : Contenu principal de la page -->
<main id="page-new-produit">
    <div class="container-full margin-top-40px flex justify-center">
        <!-- Div pour le formulaire -->
        <div class="large-8-12 flex align-center direction-column gap">
            <?php if($parametres["action"] === "create") { ?>
                <h1>Ajout d'un nouveau produit</h1>
            <?php } elseif($parametres["action"] === "update") { ?>
                <h1>Modification du <?= $parametres["produit"]->getValue("p_libelle") ?></h1>
            <?php } else { ?>
                <h1>Détail du <?= $parametres["produit"]->getValue("p_libelle") ?></h1>
            <?php } ?>

            <?= $parametres["htmlFormulaire"] ?>
            
        </div>
    </div>
</main>
<script>
    let idProduit = <?= ($parametres["produit"]->is()) ? $parametres["produit"]->id() : 0 ?>;
</script>
<script src="public/js/form_produit.js" defer></script>

