<?php

/**
 * Classe afficher_form_produit : classe du controller afficher_form_produit
 * * Rôle : Prépare et affiche le formulaire de gestion d'un produit
 */

class afficher_form_produit extends _controller {

    /**
     * Attributs
     */

    // Nom du controller
    protected $name = "AfficherFormProduit";
    // Liste des objets manipulés par le controller
    protected $objects = ["produit" => ["create","update"]]; // ["objet1" => ["action"1,"action2"...],"objet2" => ["action"1,"action2"...]]

    // * Paramètres du controller attendus en entrée
    // * id - Identifiant du produit sur lequel agir - GET - Facultatif
    // * action - Action à réaliser sur l'objet indiqué - GET - Facultatif
    protected $paramEntree = [ // ["nom_param1"=>["method"=>"POST","required"=>true],"nom_param2"=>["method"=>"POST","required"=>false]]
        "id" => ["method" => "_GET", "required" => false],
        "action" => ["method" => "_GET", "required" => false]
    ];
    
    // Type de retour
    protected $typeRetour = "pages"; // json, fragments ou pages (défaut)
    // Nom du template
    protected $template = "form_produit";
    // Tableau de paramètre du template
    protected $paramTemplate = [ // ["head" => ["title" => "", "metadescription" => "", "lang" => ""], "is_nav" => true, "is_footer" => true]
        "head" => [
            "title" => "Gestion d'un produit - Wakdo",
            "metadescription" => "Gestion des produits de Wakdo.",
            "lang" => "fr"
        ],
        "is_nav" => true,
        "is_footer" => true
    ];
    // Paramètres en sortie du controller
    protected $paramSortie = [ // ["nom_param1"=>["required"=>true],"nom_param2"=>["required"=>false]]
        "htmlFormulaire"=>["required"=>true],
        "action"=>["required"=>false],
        "produit"=>["required"=>false]
    ];
    // Besoin d'être connecté
    protected $connected = true; // True par défaut

    /**
     * Vérifie que les paramètres du controller sont bien présents et/ou leur cohérence
     *
     * @return boolean True si tout s'est bien passé, False si une erreur est survenu
     */
    public function verifParams(){
        // On vérifie déjà si les parmaètres sont OK avec la fonction parente
        if(!parent::verifParams()) {
            return false;
        }

        // S'il n'y a pas de paramètre id, on l'initialise à null
        if(!isset($this->parametres["id"])) { $this->parametres["id"] = null; }

        // S'il n'y a pas de paramètre action, on l'initialise à read
        // On initialise aussi le retour json en fonction de l'action
        if(!isset($this->parametres["action"])) {
            $this->parametres["action"] = "read";
            $this->parametres["json"] = false;
        }
        // Si l'action est mise à jour ou suppression, on attend un retour json au formulaire
        elseif($this->parametres["action"] === "update" || $this->parametres["action"] === "delete") {
            $this->parametres["json"] = true;
        }
        else {
            $this->parametres["json"] = false;
        }

        return true;
    }

    /**
     * Exécution du rôle du controller
     *
     * @return boolean True si tout s'est bien passé, False si une erreur est survenu
     */
    public function execute(){
        // On vérifie que les paramètres sont OK
        if(!$this->verifParams()) {
            //On redirige l'utilisateur vers la liste des produits
            header("Location:lister_produits.php");
            exit;
        }

        // On prépare un objet de la classe produit
        $objProduit = new produit($this->parametres["id"]);

        // On récupère le formulaire
        $this->sortie["htmlFormulaire"] = $objProduit->getFormulaire(
            $this->parametres["action"],
            $this->parametres["json"],
            true,
            [],
            []
        );
        // On récupère l'action
        $this->sortie["action"] = $this->parametres["action"];
        // On récupère l'objet produit
        $this->sortie["produit"] = $objProduit;

        return true;
    }

}
