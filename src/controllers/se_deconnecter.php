<?php

/**
 * Classe se_deconnecter : classe du controller se_deconnecter
 * * Rôle : Déconnecte l'utilisateur de l'application
 */

class se_deconnecter extends _controller {

    /**
     * Attributs
     */

    // Nom du controller
    protected $name = "SeDeconnecter";
    // Liste des objets manipulés par le controller
    protected $objects = []; // ["objet1" => ["action"1,"action2"...],"objet2" => ["action"1,"action2"...]]

    // * Paramètres du controller attendus en entrée
    // * Néant
    protected $paramEntree = []; // ["nom_param1"=>["method"=>"POST","required"=>true],"nom_param2"=>["method"=>"POST","required"=>false]]
    
    // Type de retour
    protected $typeRetour = "pages"; // json, fragments ou pages (défaut)
    // Nom du template
    protected $template = "";
    // Tableau de paramètre du template
    protected $paramTemplate = [ // ["head" => ["title" => "", "metadescription" => "", "lang" => ""], "is_nav" => true, "is_footer" => true]

    ];
    // Paramètres en sortie du controller
    protected $paramSortie = []; // ["nom_param1"=>["required"=>true],"nom_param2"=>["required"=>false]]
    // Besoin d'être connecté
    protected $connected = false; // True par défaut

    /**
     * Exécution du rôle du controller
     *
     * @return boolean True si tout s'est bien passé, False si une erreur est survenu
     */
    public function execute(){
        //On déconnecte la session
        $this->session->deconnect();

        //On redirige l'utilisateur vers la page d'accueil
        header("Location:index.php");
    }

}
