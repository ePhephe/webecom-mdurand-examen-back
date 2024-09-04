<?php

/**
 * Fragment : Formulaire d'ajout d'un produit dans une commande
 *
 * Paramètres :
 *  produit - objet du produit
 *  htmlFormulaireCdeProduit - Formulaire HTML
 */

// On récupère nos paramètres dans des variables pour plus de praticité
$produit = $parametres["produit"];

?>

<div>
    <h2><?= $produit->getValue("p_libelle") ?></h2>
    <?= $parametres["htmlFormulaireCdeProduit"] ?>
</div>

