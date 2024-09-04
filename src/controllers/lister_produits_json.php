<?php

/**
 * Classe lister_produits_json : classe du controller lister_produits_json
 * * Rôle : Retourne la liste des produits au format json
 */

class lister_produits_json extends _controller {

    /**
     * Attributs
     */

    // Nom du controller
    protected $name = "ListerProduitsJSON";
    // Liste des objets manipulés par le controller
    protected $objects = ["produit" => ["read"]]; // ["objet1" => ["action"1,"action2"...],"objet2" => ["action"1,"action2"...]]

    // * Paramètres du controller attendus en entrée
    // * idcategorie - Catégorie spécifique de produit à afficher - REQUEST - Facultatif
    protected $paramEntree = [ // ["nom_param1"=>["method"=>"POST","required"=>true],"nom_param2"=>["method"=>"POST","required"=>false]]
        "idcategorie" => ["method" => "_REQUEST", "required" => false]
    ];
    
    // Type de retour
    protected $typeRetour = "json"; // json, fragments ou pages (défaut)
    // Nom du template
    protected $template = "";
    // Tableau de paramètre du template
    protected $paramTemplate = [ // ["head" => ["title" => "", "metadescription" => "", "lang" => ""], "is_nav" => true, "is_footer" => true]
    ];
    // Paramètres en sortie du controller
    protected $paramSortie = [ // ["nom_param1"=>["required"=>true],"nom_param2"=>["required"=>false]]
        "arrayProduits"=>["required"=>true],
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

        // On regarde si on a une catégorie en paramètre
        if (!empty($this->parametres["idcategorie"])) {
            // S'il y en a un, on le récupère
            $this->parametres["filtres"][] = [
                "champ" => "p_ref_categorie",
                "valeur" => $this->parametres["idcategorie"],
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

        // On ajoute des filtres : produits disponibles et dont le stock > 0
        $this->parametres["filtres"][] = [
            "champ" => "p_stock",
            "valeur" => 0,
            "operateur" => ">",
            "table" => "produit"
        ];
        $this->parametres["filtres"][] = [
            "champ" => "p_isdispo",
            "valeur" => 1,
            "operateur" => "=",
            "table" => "produit"
        ];

        // On instancie un objet projet
        $objProduit = new produit();
        // On retourne la liste des projet et le statut en cours d'affichage
        $arrayListProduits = $objProduit->list(
            [],
            $this->parametres["filtres"],
            ["p_id" => "DESC"]
        );

        // On parcourt notre tableau pour mettre en forme au retour JSON
        $arrayResult = [];
        foreach ($arrayListProduits as $produit) {
            $arrayResult[] = $produit->getToArray();
        }

        // On prépare le retour du succès
        $this->makeRetour(true,"succes","Chargement avec succès");
        // On affecte notre fragment en sortie
        $this->sortie["arrayProduits"] = $arrayResult;
    }
}
