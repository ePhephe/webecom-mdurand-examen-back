<?php

/**
 * Template : Affichage de la liste des utilisateurs
 *
 * Paramètres :
 *  arrayListUsers - Tableau d'objets des utilisateurs à afficher
 *  roler_user - Rôle des utilisateurs à afficher
 *  statut - Statut des utilisateurs à afficher
 */

?>

<!-- Main : Contenu principal de la page -->
<main id="page-users" class="container-full">
    <!-- Titre principale de la page (H1) -->
    <h1>Gérez les utilisateurs de Wakdo</h1>

    <!-- Section pour afficher les 10 derniers projets / Swipper pour animation slider -->
    <section class="flex gap direction-column">
        <!-- Div pour afficher le formulaire -->
        <form class="filtres margin-top-20px justify-between gap">
            <h2>Filtrer les utilisateurs</h2>
            <div class="flex gap">
                <div>
                    <label for="recherche_user">Recherche d'un utilisateur : </label>
                    <input class="libelle-recherche-user" type="text" name="recherche_user" id="recherche_user" placeholder="Recherchez un utilisateur par son nom, prénom ou adresse e-mail" value="<?= $parametres["recherche_user"] ?>">
                </div>
                <div>
                    <label for="role_user">Type d'utilisateur : </label>
                    <select name="role_user" id="role_user">
                        <option value="ALL" <?= ($parametres["role_user"]==="ALL") ? "selected" : "" ?>>Tous les utilisateurs</option>
                        <option value="ADMIN" <?= ($parametres["role_user"]==="ADMIN") ? "selected" : "" ?>>Administrateur</option>
                        <option value="SUPERVISEUR" <?= ($parametres["role_user"]==="SUPERVISEUR") ? "selected" : "" ?>>Superviseur</option>
                        <option value="ACCUEIL" <?= ($parametres["role_user"]==="ACCUEIL") ? "selected" : "" ?>>Equipier d'accueil</option>
                        <option value="PREPARATION" <?= ($parametres["role_user"]==="PREPARATION") ? "selected" : "" ?>>Equipier en préparation</option>
                    </select>
                </div>
                <div>
                    <label for="statut">Statut : </label>
                    <select name="statut" id="statut">
                        <option value="ALL" <?= ($parametres["statut"]==="ALL") ? "selected" : "" ?>>Tous les utilisateurs</option>
                        <option value="A" <?= ($parametres["statut"]==="A") ? "selected" : "" ?>>Actif</option>
                        <option value="D" <?= ($parametres["statut"]==="D") ? "selected" : "" ?>>Désactivé</option>
                    </select>
                </div>
            </div>
            <input type="submit" value="Filtrer">
        </form>
        <div class="large-12-12 flex justify-between gap">
            <a class="button primary" href="afficher_form_utilisateur.php?action=create" title="Accès au formulaire de création d'un utilisateur">Nouvel Utilisateur</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Nom/Prénom</th>
                    <th>Rôle</th>
                    <th>E-mail</th>
                    <th>Statut</th>
                    <th>Réini</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="liste-users">
            <!-- Contenu du tableau des utilisateurs -->
            <?php 
                $listUsers = $parametres["arrayListUsers"];
                include "src/templates/fragments/corps_tableau_users.php";
            ?>
            </tbody>
        </table>
    </section>
</main>
