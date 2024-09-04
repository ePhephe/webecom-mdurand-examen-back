<?php

/**
 * Classe api_lister_menus : classe du controller api_lister_menus
 * * Rôle : API qui retourne la liste des menus
 */

class api_lister_menus extends _controller {

    /**
     * Attributs
     */

    // Nom du controller
    protected $name = "apiListerMenus";
    // Liste des objets manipulés par le controller
    protected $objects = []; // ["objet1" => ["action"1,"action2"...],"objet2" => ["action"1,"action2"...]]

    // * Paramètres du controller attendus en entrée
    protected $paramEntree = []; // ["nom_param1"=>["method"=>"POST","required"=>true],"nom_param2"=>["method"=>"POST","required"=>false]]
    
    // Type de retour
    protected $typeRetour = "json"; // json, fragment ou pages (défaut)
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
    function execute(){
        
        // On récupère la liste des produits qui sont des menus
        // On instancie un objet produit
        $objProduit = new produit();
        // On récupère la liste des produits
        $arrayProduits = $objProduit->list(
            [],
            [
                [
                    "champ" => "p_isdispo",
                    "valeur" => 1,
                    "operateur" => "=",
                    "table" => "produit"
                ],
                [
                    "champ" => "p_ref_categorie",
                    "valeur" => 1,
                    "operateur" => "=",
                    "table" => "produit"
                ]
            ]
        );

        // On prépare un tableau de retour
        $arrayRetour = [];
        // On parcourt tous les menus
        foreach ($arrayProduits as $idMenu => $menu) {
            // On construit un tableau des formats possibles
            $objFormat = new format();
            $arrayFormat = [];
            foreach ($objFormat->listFormatCategorie($menu->getValue("p_ref_categorie")) as $format) {
                $arrayFormat[] = [
                    "id" => $format->id(),
                    "libelle" => $format->getValue("f_libelle"),
                    "impact_prix" => $format->getValue("f_format_prix")
                ];
            }

            // On construit un tableau pour la composition
            $objMenu = new menu();
            $arrayComposition = [];
            foreach ($objMenu->listComposition($idMenu) as $composition) {
                $arrayComposition[] = [
                    "libelle_composition" => $composition->getValue("m_libelle"),
                    "id_categorie_possible" => $composition->get("m_ref_categorie_possible")->getObjet()->id(),
                    "libelle_categorie_possible" => $composition->get("m_ref_categorie_possible")->getObjet()->getValue("ca_libelle")
                ];
            }

            $arrayRetour[] = [
                "id" => $idMenu,
                "libelle" => $menu->getValue("p_libelle"),
                "description" => $menu->get("p_description")->getAffichageHTML(),
                "prix" => $menu->getValue("p_prix"),
                "stock" => $menu->getValue("p_stock"),
                "categorie" => [
                    "id" => $menu->getValue("p_ref_categorie"),
                    "libelle" => $menu->get("p_ref_categorie")->getObjet()->getValue("ca_libelle")
                ],
                "format" => $arrayFormat,
                "composition" => $arrayComposition,
                "image" => $menu->get("p_ref_piecejointe_image")->getObjet()->get_url()
            ];
        }

        $this->sortie["menus"] = $arrayRetour;
        $this->makeRetour(true,"","Chargement des menus avec succès");

        return true;
    }

}