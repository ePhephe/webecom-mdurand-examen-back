<?php

/**
 * Classe afficher_detail_commande : classe du controller afficher_detail_commande
 * * Rôle : Prépare et affiche le détail d'une commande
 */

class afficher_detail_commande extends _controller {

    /**
     * Attributs
     */

    // Nom du controller
    protected $name = "AfficherDetailCommande";
    // Liste des objets manipulés par le controller
    protected $objects = ["commande" => ["read"]]; // ["objet1" => ["action"1,"action2"...],"objet2" => ["action"1,"action2"...]]

    // * Paramètres du controller attendus en entrée
    // * idcommande - Identifiant de la commande en construction - GET - Requis
    protected $paramEntree = [ // ["nom_param1"=>["method"=>"POST","required"=>true],"nom_param2"=>["method"=>"POST","required"=>false]]
        "idcommande" => ["method" => "_GET", "required" => true]
    ];
    
    // Type de retour
    protected $typeRetour = "pages"; // json, fragments ou pages (défaut)
    // Nom du template
    protected $template = "detail_commande";
    // Tableau de paramètre du template
    protected $paramTemplate = [ // ["head" => ["title" => "", "metadescription" => "", "lang" => ""], "is_nav" => true, "is_footer" => true]
        "head" => [
            "title" => "Détail de la commande - Wakdo",
            "metadescription" => "Informations au sujet de la commande.",
            "lang" => "fr"
        ],
        "is_nav" => true,
        "is_footer" => true
    ];
    // Paramètres en sortie du controller
    protected $paramSortie = [ // ["nom_param1"=>["required"=>true],"nom_param2"=>["required"=>false]]
        "commande"=>["required"=>true]
    ];
    // Besoin d'être connecté
    protected $connected = true; // True par défaut

    /**
     * Exécution du rôle du controller
     *
     * @return boolean True si tout s'est bien passé, False si une erreur est survenu
     */
    public function execute(){
        // On vérifie que les paramètres sont OK
        if(!parent::verifParams()) {
            //On redirige l'utilisateur vers l'accueil
            header("Location:afficher_accueil.php");
            exit;
        }

        // On prépare un objet de la classe commande
        $objCommande = new commande($this->parametres["idcommande"]);

        // Sinon on récupère notre commande en sortie
        $this->sortie["commande"] = $objCommande;

        return true;
    }

}
