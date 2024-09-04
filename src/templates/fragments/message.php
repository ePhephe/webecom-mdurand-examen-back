<?php
/**
 * Fragment d'affichage des messages API Fetch ou retour PHP
 *
 * Paramètres :
 *  arrayResult - Facultatif - Tableau des messages de résultat
 */

if (isset($parametres["arrayResult"])) {
?>
    <div class="message-retour <?= $parametres["arrayResult"]["type_message"] ?>">
        <?= $parametres["arrayResult"]["message"] ?>
    </div>
<?php
}
?>

<!-- Message de retour AJAX -->
<div class="modal display-none">
    <div>

    </div>
</div>
