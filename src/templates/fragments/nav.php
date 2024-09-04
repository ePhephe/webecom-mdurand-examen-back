<?php
/**
 * Fragment du header de l'application
 */
?>

<!-- HEADER -->
<header class="flex justify-between">
    <!-- Partie de gauche : Logo + Menu -->
    <div class="flex gap align-center">
        <div class="logo">
            <img class="responsive" src="public/images/logo.png" alt="Logo Wakdo">
        </div>
        <nav class="flex align-center">
            <ul class="flex gap align-center">
                <li>
                    <a href="afficher_accueil.php">Accueil</a>
                </li>
                <?php if($this->session->userConnected()->getValue("u_role_user") === "ADMIN") { ?>
                <li>
                    <a href="lister_produits.php">Produits</a>
                </li>
                <li>
                    <a href="lister_utilisateurs.php">Utilisateurs</a>
                </li>
                <?php } ?>
            </ul>
        </nav>
    </div>
    <!-- Partie de droite : affichage du type d'accès + Déconnexion -->
    <nav class="flex align-center">
        <ul class="flex gap align-center">
            <li>
                Accès <?= $this->session->userConnected()->get("u_role_user")->getListLibelle() ?>
            </li>
            <li>
                <a href="se_deconnecter.php">Se déconnecter</a>
            </li>
        </ul>
    </nav>
</header>
<div class="header-fixed">

</div>
