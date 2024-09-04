<?php

/**
 * Classe afficher_reini_password : classe du controller afficher_reini_password
 * * Rôle : Prépare et affiche le formulaire de réinitialisation du mot de passe
 */

class afficher_reini_password extends _controller {

    /**
     * Attributs
     */

    // Nom du controller
    protected $name = "AfficherReiniPassword";
    // Liste des objets manipulés par le controller
    protected $objects = []; // ["objet1" => ["action"1,"action2"...],"objet2" => ["action"1,"action2"...]]

    // * Paramètres du controller attendus en entrée
    // * Néant
    protected $paramEntree = []; // ["nom_param1"=>["method"=>"POST","required"=>true],"nom_param2"=>["method"=>"POST","required"=>false]]
    
    // Type de retour
    protected $typeRetour = "pages"; // json, fragments ou pages (défaut)
    // Nom du template
    protected $template = "form_reini_password";
    // Tableau de paramètre du template
    protected $paramTemplate = [ // ["head" => ["title" => "", "metadescription" => "", "lang" => ""], "is_nav" => true, "is_footer" => true]
        "head" => [
            "title" => "Réinitialisation du mot de passe - Wakdo",
            "metadescription" => "Pour accéder à votre compte, connectez-vous avec votre adresse e-mail.",
            "lang" => "fr"
        ],
        "is_nav" => false,
        "is_footer" => false
    ];
    // Paramètres en sortie du controller
    protected $paramSortie = ["htmlFormulaireReini"=>["required"=>true],"arrayResult"=>["required"=>false]]; // ["nom_param1"=>["required"=>true],"nom_param2"=>["required"=>false]]
    // Besoin d'être connecté
    protected $connected = false; // True par défaut

    /**
     * Exécution du rôle du controller
     *
     * @return boolean True si tout s'est bien passé, False si une erreur est survenu
     */
    public function execute(){
        // On appelle la vérification des paramètres
        if(!parent::verifParams()) {
            return false;
        }

        // On récupère un objet d'utilisateur via la session
        $objUser = $this->session->userConnected();

        // On appelle la méthode qui va générer le formulaire
        $this->sortie["htmlFormulaireReini"] = $objUser->formulaireReiniPassword();
    }

}
