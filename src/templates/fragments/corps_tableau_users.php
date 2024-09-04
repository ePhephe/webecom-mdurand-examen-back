<?php 
/**
 * Fragment pour afficher le contenu du tableau des utilisateurs
 * 
 *  Paramètres :
 *  listUsers - Tableau d'objets des utilisateurs
 */

foreach ($listUsers as $user) { ?>
    <tr>
        <td><?= $user->getValue("u_nom") . " " . $user->getValue("u_prenom") ?></td>
        <td><?= $user->get("u_role_user")->getListLibelle() ?></td>
        <td><?= $user->getValue("u_email") ?></td>
        <td><?= ($user->getValue("u_statut")==="A") ? '<div class="dispo stock"></div>' : '<div class="dispo rupture"></div>' ?></td>
        <td><?= ($user->getValue("u_expiration_reini_password") != "") ? "En cours" : "" ?></td>
        <td>
            <?php if ($user->id() != $this->session->id() && $user->getValue("u_statut") != "D") { ?>
                <a href="afficher_form_utilisateur.php?id=<?= $user->id() ?>&action=update" alt="Accès au formulaire de modification de l'utilisateur"><img src="public/images/icons/edit.png" alt="Icone de modification"></a>
                <a href="supprimer_utilisateur.php?id=<?= $user->id() ?>" alt="Désactivation d'un utilisateur"><img src="public/images/icons/trash.png" alt="Icone de désactivation"></a>
            <?php } ?>
        </td>
    </tr>
<?php } ?>
