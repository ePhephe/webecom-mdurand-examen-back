<?php

/**
 * Classe afficher_new_password : classe du controller afficher_new_password
 * * Rôle : Prépare et affiche le formulaire de définition du nouveau mot de passe
 */

class afficher_new_password extends _controller {

    /**
     * Attributs
     */

    // Nom du controller
    protected $name = "AfficherNewPassword";
    // Liste des objets manipulés par le controller
    protected $objects = []; // ["objet1" => ["action"1,"action2"...],"objet2" => ["action"1,"action2"...]]

    // * Paramètres du controller attendus en entrée
    // * validator - Token de réinitialisation - GET - Requis
    // * selector - Clé sélecteur du token de réinitialisation - GET - Requis
    protected $paramEntree = [ // ["nom_param1"=>["method"=>"POST","required"=>true],"nom_param2"=>["method"=>"POST","required"=>false]]
        "validator"=>["method"=>"_GET","required"=>true],
        "selector"=>["method"=>"_GET","required"=>true]
    ];
    
    // Type de retour
    protected $typeRetour = "pages"; // json, fragments ou pages (défaut)
    // Nom du template
    protected $template = "form_new_password";
    // Tableau de paramètre du template
    protected $paramTemplate = [ // ["head" => ["title" => "", "metadescription" => "", "lang" => ""], "is_nav" => true, "is_footer" => true]
        "head" => [
            "title" => "Définition de votre nouveau mot de passe - Wakdo",
            "metadescription" => "Pour accéder à votre compte, définissez votre nouveau mot de passe.",
            "lang" => "fr"
        ],
        "is_nav" => true,
        "is_footer" => false
    ];
    // Paramètres en sortie du controller
    protected $paramSortie = ["htmlFormulaireNewPassword"=>["required"=>true],"arrayResult"=>["required"=>false]]; // ["nom_param1"=>["required"=>true],"nom_param2"=>["required"=>false]]
    // Besoin d'être connecté
    protected $connected = false; // True par défaut

    /**
     * Exécution du rôle du controller
     *
     * @return boolean True si tout s'est bien passé, False si une erreur est survenu
     */
    public function execute(){
        // On prépare un objet de la classe user
        $nomObjet = $this->get("session")->getTableUser();
        $objUtilisateur = new $nomObjet();

        // On vérifie que les paramètres nécessaires à afficher le formulaire sont présents
        if(!parent::verifParams()){
            // Sinon on redirige vers la page de connexion
            header("Location: ".$objUtilisateur->getURLFormulaire("formLogin")."?redirect=echecnewpassword");
            exit;
        }

        //On récupère les paramètres dans des variables
        $strSelector = $this->parametres["selector"];
        $strToken = $this->parametres["validator"];

        // On récupère le formulaire pour demander le nouveau mot de passe
        $this->sortie["htmlFormulaireNewPassword"] = $objUtilisateur->formulaireNewPassword(
            $strSelector,
            $strToken
        );

        return true;
    }

}
