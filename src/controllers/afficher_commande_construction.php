<?php

/**
 * Classe afficher_commande_construction : classe du controller afficher_commande_construction
 * * Rôle : Prépare et affiche la page de construction du contenu d'une commande
 */

class afficher_commande_construction extends _controller {

    /**
     * Attributs
     */

    // Nom du controller
    protected $name = "AfficherCommandeConstruction";
    // Liste des objets manipulés par le controller
    protected $objects = ["commande" => ["create"]]; // ["objet1" => ["action"1,"action2"...],"objet2" => ["action"1,"action2"...]]

    // * Paramètres du controller attendus en entrée
    // * id - Identifiant de la commande en construction - GET - Requis
    protected $paramEntree = [ // ["nom_param1"=>["method"=>"POST","required"=>true],"nom_param2"=>["method"=>"POST","required"=>false]]
        "idcommande" => ["method" => "_GET", "required" => true]
    ];
    
    // Type de retour
    protected $typeRetour = "pages"; // json, fragments ou pages (défaut)
    // Nom du template
    protected $template = "form_commande_construction";
    // Tableau de paramètre du template
    protected $paramTemplate = [ // ["head" => ["title" => "", "metadescription" => "", "lang" => ""], "is_nav" => true, "is_footer" => true]
        "head" => [
            "title" => "Construction d'une commande - Wakdo",
            "metadescription" => "Construction des commandes de Wakdo.",
            "lang" => "fr"
        ],
        "is_nav" => true,
        "is_footer" => true
    ];
    // Paramètres en sortie du controller
    protected $paramSortie = [ // ["nom_param1"=>["required"=>true],"nom_param2"=>["required"=>false]]
        "arrayCategorie"=>["required"=>true],
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

        // Si la commande est dans un autre statut que Créée, on redirige vers l'accueil
        if($objCommande->getValue("c_statut") != "C") {
            //On redirige l'utilisateur vers l'accueil
            header("Location:afficher_accueil.php");
            exit;
        }

        // Sinon on récupère notre commande en sortie
        $this->sortie["commande"] = $objCommande;

        // On récupère la liste des catégories
        $objCategorie = new categorie();
        $this->sortie["arrayCategorie"] = $objCategorie->list();

        return true;
    }

}
