<?php

/**
 * Template : Affichage de la liste des produits
 *
 * Paramètres :
 *  arrayListProduits - Tableau d'objets des produits à afficher
 *  arrayListCategories - Tableau d'objets des catégories à afficher
 *  libelle - Facultatif - Filtre de libelle de recherche pour un produit
 *  isdispo - Facultatif - Filtre de disponiblité
 *  categorie - Facultatif - Filtre de catégorie
 */

?>

<!-- Main : Contenu principal de la page -->
<main id="page-produits" class="container-full">
    <section class="flex gap direction-column">
    <?php
    // Dans le cas où on est un gestionnaire, on affiche sous forme de tableau
    if($this->session->userConnected()->getValue("u_role_user") === "ADMIN") {
    ?>
        <!-- Titre principale de la page (H1) -->
        <h1>Liste des produits Wabdo</h1>
        <!-- Formulaire de filtre pour les produits -->
        <form class="filtres margin-top-20px flex flex-direction gap">
            <h2>Filtrer les produits</h2>
            <div class="flex gap">
                <div>
                    <label for="libelle">Rechercher un produit :</label>
                    <input class="libelle-recherche" type="text" name="libelle" id="libelle" placeholder="Chercher un produit par son nom" value="<?= (isset($parametres["libelle"])) ? $parametres["libelle"] : "" ?>">
                </div>
                <div>
                    <label for="isdispo">Disponibilité :</label>
                    <select name="isdispo" id="isdispo">
                        <option value="" <?= (isset($parametres["isdispo"])&&$parametres["isdispo"]==="") ? "selected" : "" ?> >Tous</option>
                        <option value="1" <?= (isset($parametres["isdispo"])&&$parametres["isdispo"]==="1") ? "selected" : "" ?> >Oui</option>
                        <option value="0" <?= (isset($parametres["isdispo"])&&$parametres["isdispo"]==="0") ? "selected" : "" ?> >Non</option>
                    </select>
                </div>
                <div>
                    <label for="categorie">Catégorie :</label>
                    <select name="categorie" id="categorie">
                        <option value="">Toutes</option>
                        <?php foreach ($parametres["arrayListCategories"] as $categorie) { ?>
                        <option value="<?= $categorie->id() ?>" <?= (isset($parametres["categorie"])&&$parametres["categorie"]==$categorie->id()) ? "selected" : "" ?>><?= $categorie->getValue("ca_libelle") ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <input type="submit" value="Rechercher">
        </form>
        <div class="flex gap">
            <a class="button primary" href="afficher_form_produit.php?action=create" title="Afficher le formulaire d'ajout d'un produit">Ajouter un nouveau produit</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th></th>
                    <th>Nom</th>
                    <th>Prix TTC</th>
                    <th>Catégorie</th>
                    <th>Disponibilité</th>
                    <th>Stock</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
        <!-- Ligne d'un tableau pour un projet -->
        <?php foreach ($parametres["arrayListProduits"] as $produit) { ?>
                <tr>
                    <td class="liste-produit-image">
                        <img class="responsive" src="<?= $produit->get("p_ref_piecejointe_image")->getObjet()->get_url() ?>" alt="">
                    </td>
                    <td><?= $produit->getValue("p_libelle") ?></td>
                    <td><?= $produit->getValue("p_prix") ?> €</td>
                    <td><?= $produit->get("p_ref_categorie")->getObjet()->getValue("ca_libelle") ?></td>
                    <td><?= ($produit->getValue("p_isdispo")==="1") ? '<div class="dispo stock"></div>' : '<div class="dispo rupture"></div>' ?></td>
                    <td><?= $produit->getValue("p_stock") ?></td>
                    <td class="action">
                        <a href="afficher_form_produit.php?action=read&id=<?= $produit->id() ?>" title="Accès au détail du <?= $produit->getValue("p_libelle") ?>">
                            <img src="public/images/icons/info.png" alt="Icone de détail">
                        </a>
                        <a href="afficher_form_produit.php?action=update&id=<?= $produit->id() ?>" title="Modifier le <?= $produit->getValue("p_libelle") ?>">
                            <img src="public/images/icons/edit.png" alt="Icone de modfication">
                        </a>
                        <a class="btn_supprimer" href="supprimer_produit.php?id=<?= $produit->id() ?>" title="Supprimer le <?= $produit->getValue("p_libelle") ?>">
                            <img src="public/images/icons/trash.png" alt="Icone de suppression">
                        </a>
                    </td>
                </tr>
        <?php } ?>
            </tbody>
        </table>
    <?php
    }
    ?>
</main>
<script src="public/js/list_produits.js" defer></script>
