<?php

/**
 * Template : Formulaire de gestion des produits dans les commandes
 *
 * Paramètres :
 *  arrayCategorie - Tableau des objets des catégories
 *  commande - Objet de la commande
 */

// On récupère notre objet commande dans une variable pour plus de praticité
$objCommande = $parametres["commande"];

?>

<!-- Main : Contenu principal de la page -->
<main id="page-construction-commande">
    <div class="container-full margin-top-40px flex gap direction-column">
        <!-- Div pour le formulaire -->
        <h1>Construction de la commande</h1>
        <div class="commentaire-commande">
            <span>Commentaire :</span><br> <?= $objCommande->get("c_commentaire")->getAffichageHTML() ?>
        </div>
        <!-- Informations sur la commande -->
        <div class="infos-commande flex gap direction-column">
            <!-- En-tête avec les informations globales -->
            <div class="header-commande flex direction-column">
                <div><span>Numéro :</span> <?= $objCommande->getValue("c_num_cde") ?></div>
                <div><span>Référence :</span> <?= $objCommande->getValue("c_ref_cde") ?></div>
                <div><span>Type de service :</span> <?= $objCommande->get("c_type_service")->getListLibelle() ?></div>
                <div><span>Heure de livraison prévue :</span> <?= $objCommande->get("c_datetime_livraison")->getFormat("affichage") ?></div>
            </div>
            <!-- Contenu de la commande -->
            <div id="produits_commande">

            </div>
            <!-- Totale de la commande et bouton envoyer -->
            <div class="footer-commande flex gap direction-column">
                <div class="total flex gap justify-between">
                    <div>Total (TTC)</div>
                    <div id="total_commande">0.00 €</div>
                </div>
                <div class="flex gap justify-between">
                    <a id="btn_retour" class="button secondary" href="afficher_accueil.php">Retour</a>
                    <a id="btn_finaliser" class="button primary" href="finaliser_commande.php">Envoyer</a>
                </div>
            </div>
        </div>
        <!-- Affichage des produits -->
        <div class="selection-produits flex gap">
            <!-- Entête avec le choix de la catégorie -->
            <div class="large-12-12 flex gap align-center justify-center">
                <label for="ref_categorie">Catégorie de produit :</label>
                <select name="ref_categorie" id="ref_categorie">
                    <?php foreach ($parametres["arrayCategorie"] as $categorie) { ?>
                        <option value="<?= $categorie->id() ?>"><?= $categorie->getValue("ca_libelle") ?></option>
                    <?php } ?>
                </select>
            </div>
            <!-- Liste des produits -->
            <div id="list_produits" class="large-12-12 flex gap">

            </div>
        </div>
    </div>
    <!-- Modal pour choisir les informations sur le produit -->
    <div id="container_modal_choix" class="flex align-center justify-center display-none">
        <div id="modal_choix">

        </div>
    </div>
</main>
<script>
    // On récupère l'id de la commande pour le javascript
    let idCommande = <?= ($parametres["commande"]->is()) ? $parametres["commande"]->id() : 0 ?>;
    // On définit par défaut la catégorie affichée à 1 (les menus)
    let idCategorie = 1;
</script>
<script src="public/js/form_commande_construction.js" defer></script>