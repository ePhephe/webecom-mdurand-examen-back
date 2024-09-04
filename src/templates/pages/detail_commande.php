<?php

/**
 * Template : Détails d'une commande
 *
 * Paramètres :
 *  commande - Commande à afficher
 */

// On récupère notre objet commande dans une variable pour plus de praticité
$objCommande = $parametres["commande"];

?>

<!-- Main : Contenu principal de la page -->
<main id="page-construction-commande">
    <div class="container-full margin-top-40px flex gap direction-column align-center">
        <!-- Div pour le formulaire -->
        <div class="large-12-12 medium-6-8 small-4-4 flex align-center gap">
            <a class="button secondary" href="afficher_accueil.php" title="Retour à la liste des commandes">Retour à la liste</a>
            <h1>Détail de la commande <?= $objCommande->getValue("c_num_cde") ?></h1>
            <?php if ($this->permission->verifPermission("commande", "create") && $objCommande->getValue("c_statut") === "C") { ?>
                <a href="afficher_commande_construction.php?idcommande=<?= $objCommande->id() ?>"><img src="public/images/icons/add-cart.png" alt="Icone de détail"></a>
            <?php } ?>
            <?php if ($this->permission->verifPermission("commande", "preparer") && $objCommande->getValue("c_statut") === "AP") { ?>
                <a class="action" href="preparer_commande.php?idcommande=<?= $objCommande->id() ?>"><img src="public/images/icons/ready.png" alt="Icone de détail"></a>
            <?php } ?>
            <?php if ($this->permission->verifPermission("commande", "livrer") && $objCommande->getValue("c_statut") === "P") { ?>
                <a class="action" href="livrer_commande.php?idcommande=<?= $objCommande->id() ?>"><img src="public/images/icons/delivery.png" alt="Icone de détail"></a>
            <?php } ?>
            <?php if ($this->permission->verifPermission("commande", "delete") && $objCommande->getValue("c_statut") !== "S") { ?>
                <a class="action" href="supprimer_commande.php?idcommande=<?= $objCommande->id() ?>"><img src="public/images/icons/trash.png" alt="Icone de détail"></a>
            <?php } ?>
        </div>
        <div class="commentaire-commande">
            <span>Commentaire :</span><br> <?= $objCommande->get("c_commentaire")->getAffichageHTML() ?>
        </div>
        <!-- Informations sur la commande -->
        <div class="details-commande large-12-12 flex gap direction-column">
            <!-- En-tête avec les informations globales -->
            <div class="header-commande large-12-12 flex gap">
                <div class="large-6-12">
                    <div>
                        <span>Numéro :</span> <?= $objCommande->getValue("c_num_cde") ?>
                    </div>
                    <div>
                        <span>Référence :</span> <?= $objCommande->getValue("c_ref_cde") ?>
                    </div>
                    <div>
                        <span>Heure de livraison prévue :</span> <?= $objCommande->get("c_datetime_livraison")->getFormat("affichage") ?>
                    </div>
                </div>
                <div class="large-6-12">
                    <div>
                        <span>Statut :</span> <?= $objCommande->get("c_statut")->getListLibelle() ?>
                    </div>
                    <div>
                        <span>Type de service :</span> <?= $objCommande->get("c_type_service")->getListLibelle() ?>
                    </div>
                    <div>
                        <?php if(!empty($objCommande->getValue("c_num_chevalet"))) { ?>
                        <span>Chevalet :</span> <?= $objCommande->getValue("c_num_chevalet") ?>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <h2>Produits</h2>
            <!-- Contenu de la commande -->
            <div id="produits_commande" class="flex gap">
                
                <?php foreach ($objCommande->listProduits() as $produitCde) { 
                    // On récupère l'objet du produit
                    $objProduit = $produitCde->get("cp_ref_produit")->getObjet();
                ?>
                    <?php if(!$produitCde->getValue("cp_ref_produit_menu")) { ?>
                    <div class="large-4-12">
                        <?= $produitCde->getValue("cp_quantite")." x ".$objProduit->getValue("p_libelle") ?>
                        <ul>
                            <?php foreach ($objCommande->listProduits() as $produitCdeMenu) { 
                                // On récupère l'objet du produit du menu
                                $objProduitMenu = $produitCdeMenu->get("cp_ref_produit")->getObjet();
                            ?>
                                <?php if($produitCde->id() === $produitCdeMenu->getValue("cp_ref_menu_commande")) { ?>
                                    <li><?= $objProduitMenu->getValue("p_libelle") ?></li>
                                <?php } ?>
                            <?php } ?>
                        </ul>
                    </div>
                    <?php } ?>
                <?php } ?>
            </div>
            <!-- Totale de la commande et bouton envoyer -->
            <div class="footer-commande flex gap align-center">
                <div>Total (TTC)</div>
                <div id="total_commande"><?= $objCommande->getTotalCommande() ?> €</div>
            </div>
        </div>
    </div>
</main>
<script src="public/js/detail.js" defer></script>