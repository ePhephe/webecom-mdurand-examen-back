<?php

/**
 * Contrôleur : Controleur principale
 * Paramètres :
 *  @param variable GET - Page à afficher
 */

/**
 * Initialisation
 */
// On inclut le fichier init
require_once "src/utils/init.php";

/**
 * Récupération des paramètres
 */

// On vérifie qu'une page est bien passé en paramètre
if(isSet($_GET["page"])) {
    if(isSet($_GET["api"]) && $_GET["api"] === "true") {
        // On récupère la page
        $page = "api_".$_GET["page"];
    }
    else {
        // On récupère la page
        $page = $_GET["page"];
    }
}
else {
    echo "Erreur dans l'application";
    exit();
}

/**
 * Traitements
 */

// On instancie un objet controller pour la page concerné
$objController = new $page($_REQUEST);
// On lance l'execution du controller
$objController->execute();

/**
 * Affichage ou Retour
 */
$objController->render();