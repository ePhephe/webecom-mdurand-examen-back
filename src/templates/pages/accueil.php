<?php

/**
 * Template : Page d'accueil de l'application
 *
 * Paramètres :
 *  arrayListCommandes - Tableau d'objets des commandes
 */

?>

<!-- Div qui va contenir notre tableau des commandes -->
<div class="container-full large-12-12 flex align-center direction-column gap">
    <h1>Bienvenue sur votre interface de gestion Wakdo !</h1>

    <div class="large-12-12 flex align-center">
    <?php if($this->permission->verifPermission("commande","create")) { ?>
        <a class="button primary" href="afficher_form_commande.php?action=create" title="Créer une nouvelle commande">Nouvelle commande</a>
    <?php } ?>
    </div>

    <table class="large-12-12">
        <thead>
            <tr>
                <th>Numéro</th>
                <th>Référence</th>
                <th>Service</th>
                <th>Statut</th>
                <th>Livraison</th>
                <th>Total</th>
                <th></th>
            </tr>
        </thead>
        <tbody id="container-commandes">
        <?php foreach ($parametres["arrayListCommandes"] as $commande) { ?>
            <tr data-id-commande="<?= $commande->id() ?>">
                <td><?= $commande->getValue("c_num_cde") ?></td>
                <td><?= $commande->getValue("c_ref_cde") ?></td>
                <td>
                    <?= ($commande->getValue("c_type_service")==="AE") ? "A emporter" : "Sur place (".$commande->getValue("c_num_chevalet").")" ?>
                </td>
                <td><?= $commande->get("c_statut")->getListLibelle() ?></td>
                <td><?= $commande->get("c_datetime_livraison")->getFormat("affichage") ?></td>
                <td><?= number_format($commande->getTotalCommande(),2) ?> €</td>
                <td>
                    <a href="afficher_detail_commande.php?idcommande=<?= $commande->id() ?>" title="Voir le détail de la commande <?= $commande->getValue("c_num_cde") ?>">
                        <img src="public/images/icons/info.png" alt ="Icone de détail">
                    </a>
                    <?php if($this->permission->verifPermission("commande","create") && $commande->getValue("c_statut") === "C") { ?>
                    <a href="afficher_commande_construction.php?idcommande=<?= $commande->id() ?>" title="Construire la commande <?= $commande->getValue("c_num_cde") ?>">
                        <img src="public/images/icons/add-cart.png" alt ="Icone de panier avec un plus">
                    </a>
                    <?php } ?>
                    <?php if($this->permission->verifPermission("commande","preparer") && $commande->getValue("c_statut") === "AP") { ?>
                    <a class="action" href="preparer_commande.php?idcommande=<?= $commande->id() ?>" title="Passer la commande <?= $commande->getValue("c_num_cde") ?> prête">
                        <img src="public/images/icons/ready.png" alt ="Icone de commande prête">
                    </a>
                    <?php } ?>
                    <?php if($this->permission->verifPermission("commande","livrer") && $commande->getValue("c_statut") === "P") { ?>
                    <a class="action" href="livrer_commande.php?idcommande=<?= $commande->id() ?>" title="Passer la commande <?= $commande->getValue("c_num_cde") ?> livrée">
                        <img src="public/images/icons/delivery.png" alt ="Icone de livraison">
                    </a>
                    <?php } ?>
                    <?php if($this->permission->verifPermission("commande","delete") && $commande->getValue("c_statut") !== "S") { ?>
                    <a class="action" href="supprimer_commande.php?idcommande=<?= $commande->id() ?>" title="Supprimer la commande <?= $commande->getValue("c_num_cde") ?>">
                        <img src="public/images/icons/trash.png" alt ="Icone de suppression">
                    </a>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<script src="public/js/accueil.js" defer></script>
