<?php

/**
 * Classe lister_categories_menu : classe du controller lister_categories_menu
 * * Rôle : Retourne la liste des catégories pour ajout dans les choix d'un menu
 */

class lister_categories_menu extends _controller {

    /**
     * Attributs
     */

    // Nom du controller
    protected $name = "ListerCategoriesMenu";
    // Liste des objets manipulés par le controller
    protected $objects = []; // ["objet1" => ["action"1,"action2"...],"objet2" => ["action"1,"action2"...]]

    // * Paramètres du controller attendus en entrée
    // * Néant
    protected $paramEntree = [ // ["nom_param1"=>["method"=>"POST","required"=>true],"nom_param2"=>["method"=>"POST","required"=>false]]
        ["idproduit"=>["method"=>"GET","required"=>false]]
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
        "arrayCategorie"=>["required"=>true],
        "arrayMenu"=>["required"=>false]
    ];
    // Besoin d'être connecté
    protected $connected = true; // True par défaut

    /**
     * Exécution du rôle du controller
     *
     * @return boolean True si tout s'est bien passé, False si une erreur est survenu
     */
    public function execute(){
        // On instancie un objet de categorie
        $objCategorie = new categorie();

        // On initialise notre fragment de retour
        $arrayCategorie = $objCategorie->list(
            [],
            [
                [
                    "champ" => "ca_id",
                    "valeur" => 1,
                    "operateur" => "<>",
                    "table" => "categorie"
                ]
            ]
        );

        // On parcourt nos résultats pour les transformer pour le retour
        $arrayResult = [];
        foreach ($arrayCategorie as $categorie) {
            $arrayResult[] = $categorie->getToArray();
        }

        // Si on a un id de produit, on récupère la composition possible actuelle du menu
        if(isset($this->parametres["idproduit"])) {
            // On instancie un objet menu
            $objMenu = new menu();
            // On récupère la liste de la composition
            $arrayComposition = $objMenu->listComposition($this->parametres["idproduit"]);
            // On parcourt nos résultats pour les transformer pour le retour
            $arrayValue = [];
            foreach ($arrayComposition as $menu) {
                $arrayValue[] = $menu->getToArray();
            }

            $this->sortie["arrayMenu"] = $arrayValue;
        }
        
        // On prépare le retour du succès
        $this->makeRetour(true,"succes","Chargement avec succès");
        // On affecte notre fragment en sortie
        $this->sortie["arrayCategorie"] = $arrayResult;
    }

}
