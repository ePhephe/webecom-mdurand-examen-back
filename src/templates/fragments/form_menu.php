<?php

/**
 * Fragement : Formulaire d'ajout d'un menu à une commande
 *
 * Paramètres :
 *  arrayCompositionChoix - Tableau des compositions du menu avec les choix possibles
 *  arrayListeFormatMenu - Liste des formats possibles pour le menu
 *  produit - Produit Menu concerné
 */

// On récupère nos paramètres dans des variables pour plus de praticité
$arrayCompositionChoix = $parametres["arrayCompositionChoix"];
$arrayListeFormatMenu = $parametres["arrayListeFormatMenu"];
$produitMenu = $parametres["produit"];

?>

<form id="composition-menu">
    <h2><?= $produitMenu->getValue("p_libelle") ?></h2>
    <input type="hidden" name="cp_ref_produit_menu" id="cp_ref_produit_menu" value="<?= $produitMenu->id() ?>">
    <div class="flex gap direction-column">
        <label for="cp_ref_format">Choix du format</label>
        <select name="cp_ref_format" id="cp_ref_format">
            <?php foreach ($arrayListeFormatMenu as $format) { ?>
                <option data-prix="<?= $format->getValue("f_format_prix") ?>" value="<?= $format->id() ?>"><?= $format->getValue("f_libelle") ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="contenu-menu flex gap direction-column">
        <?php
            $index = 0;
            foreach ($arrayCompositionChoix as $composition) {
        ?>
            <div class="flex gap direction-column">
                <label for="cp_ref_produit_<?= $index ?>"><?= $composition->getValue("m_libelle") ?></label>
                <select name="cp_ref_produit[]" id="cp_ref_format_<?= $index ?>" title="Choix du produit <?= $composition->getValue("m_libelle") ?>">
                    <?php foreach ($composition->get_produits() as $produit) { ?>
                        <option value="<?= $produit->id() ?>"><?= $produit->getValue("p_libelle") ?></option>
                    <?php } ?>
                </select>
            </div>
        <?php
                $index++;
            }
        ?>
    </div>
    <div class="flex gap justify-end">
        <input type="button" id="btn-cancel" value="Annuler">
        <input type="submit" value="Ajouter">
    </div>
</form>
