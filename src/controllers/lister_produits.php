<?php

/**
 * Classe lister_produits : classe du controller lister_produits
 * * Rôle : Prépare et affiche la liste des produits
 */

class lister_produits extends _controller {

    /**
     * Attributs
     */

    // Nom du controller
    protected $name = "ListerProduits";
    // Liste des objets manipulés par le controller
    protected $objects = ["produit" => ["read"]]; // ["objet1" => ["action"1,"action2"...],"objet2" => ["action"1,"action2"...]]

    // * Paramètres du controller attendus en entrée
    // * libelle - Chaîne de caractères pour chercher un produit par son nom - REQUEST - Facultatif
    // * isdispo - 0 ou 1, chercher un produit par sa disponibilité - REQUEST - Facultatif
    // * categorie - Catégorie spécifique de produit à afficher - REQUEST - Facultatif
    protected $paramEntree = [ // ["nom_param1"=>["method"=>"POST","required"=>true],"nom_param2"=>["method"=>"POST","required"=>false]]
        "libelle" => ["method" => "_REQUEST", "required" => false],
        "isdispo" => ["method" => "_REQUEST", "required" => false],
        "categorie" => ["method" => "_REQUEST", "required" => false]
    ];
    
    // Type de retour
    protected $typeRetour = "pages"; // json, fragments ou pages (défaut)
    // Nom du template
    protected $template = "list_produits";
    // Tableau de paramètre du template
    protected $paramTemplate = [ // ["head" => ["title" => "", "metadescription" => "", "lang" => ""], "is_nav" => true, "is_footer" => true]
        "head" => [
            "title" => "Tous les produits Wakdo",
            "metadescription" => "Gestion des produits de Wakdo",
            "lang" => "fr"
        ],
        "is_nav" => true,
        "is_footer" => true
    ];
    // Paramètres en sortie du controller
    protected $paramSortie = [ // ["nom_param1"=>["required"=>true],"nom_param2"=>["required"=>false]]
        "arrayListProduits"=>["required"=>true],
        "arrayListCategories"=>["required"=>true],
        "libelle"=>["required"=>false],
        "isdispo"=>["required"=>false],
        "categorie"=>["required"=>false]
    ]; 
    // Besoin d'être connecté
    protected $connected = true; // True par défaut

    /**
     * Vérifie que les paramètres du controller sont bien présents et/ou leur cohérence
     *
     * @return boolean True si tout s'est bien passé, False si une erreur est survenu
     */
    public function verifParams(){
        // On appelle la méthod parente
        parent::verifParams();

        // On définit nos paramètres de filtres
        $this->parametres["filtres"] = [];

        // On regarde si on a un libelle en paramètre
        if (!empty($this->parametres["libelle"])) {
            // S'il y en a un, on le récupère
            $this->parametres["filtres"][] = [
                "champ" => "p_libelle",
                "valeur" => $this->parametres["libelle"],
                "operateur" => "LIKE",
                "table" => "produit"
            ];
        }
        // On regarde si on a isdispo en paramètre
        if (isset($this->parametres["isdispo"]) && $this->parametres["isdispo"] != "") {
            // S'il y en a un, on le récupère
            $this->parametres["filtres"][] = [
                "champ" => "p_isdispo",
                "valeur" => $this->parametres["isdispo"],
                "operateur" => "=",
                "table" => "produit"
            ];
        }
        // On regarde si on a une catégorie en paramètre
        if (!empty($this->parametres["categorie"])) {
            // S'il y en a un, on le récupère
            $this->parametres["filtres"][] = [
                "champ" => "p_ref_categorie",
                "valeur" => $this->parametres["categorie"],
                "operateur" => "=",
                "table" => "produit"
            ];
        }

        return true;
    }

    /**
     * Exécution du rôle du controller
     *
     * @return boolean True si tout s'est bien passé, False si une erreur est survenu
     */
    public function execute(){
        // On appelle la fonction de vérification des paramètres parente
        $this->verifParams();

        // On instancie un objet projet
        $objProduit = new produit();
        // On retourne la liste des projet et le statut en cours d'affichage
        $this->sortie["arrayListProduits"] = $objProduit->list(
            [],
            $this->parametres["filtres"],
            ["p_id" => "DESC"]
        );

        // On instancie un objet catégorie
        $objCategorie = new categorie();
        // On retourne la liste des catégories
        $this->sortie["arrayListCategories"] = $objCategorie->list();

        // On retourne les paramètres de filtre en cours
        $this->sortie["libelle"] = (isset($this->parametres["libelle"])) ? $this->parametres["libelle"] : "";
        $this->sortie["isdispo"] = (isset($this->parametres["isdispo"])) ? $this->parametres["isdispo"] : "";
        $this->sortie["categorie"] = (isset($this->parametres["categorie"])) ? $this->parametres["categorie"] : "";
    }

}
