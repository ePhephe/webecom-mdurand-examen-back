<?php

/**
 * Classe api_lister_produits : classe du controller api_lister_produits
 * * Rôle : API qui retourne la liste des produits (hors menu)
 */

class api_lister_produits extends _controller {

    /**
     * Attributs
     */

    // Nom du controller
    protected $name = "apiListerProduits";
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
        // On récupère la liste des produits sauf les menus
        // On instancie un objet catégorie
        $objCategorie = new categorie();
        // On récupère la liste des catégories, sauf les menus
        $arrayCategories = $objCategorie->list(
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
        // On parcourts toutes nos catégories
        $arrayRetour = [];
        foreach ($arrayCategories as $idCategorie => $categorie) {
            // On récupère les produits de la catégorie
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
                        "valeur" => $idCategorie,
                        "operateur" => "<>",
                        "table" => "produit"
                    ]
                ]
            );

            // On prépare un tableau de retour
            $arrayRetourProduits = [];
            // On parcourt tous les menus
            foreach ($arrayProduits as $idProduit => $produit) {
                // On construit un tableau des formats possibles
                $objFormat = new format();
                $arrayFormat = [];
                foreach ($objFormat->listFormatCategorie($produit->getValue("p_ref_categorie")) as $format) {
                    $arrayFormat[] = [
                        "id" => $format->id(),
                        "libelle" => $format->getValue("f_libelle"),
                        "impact_prix" => $format->getValue("f_format_prix")
                    ];
                }

                $arrayRetourProduits[] = [
                    "id" => $idProduit,
                    "libelle" => $produit->getValue("p_libelle"),
                    "description" => $produit->get("p_description")->getAffichageHTML(),
                    "prix" => $produit->getValue("p_prix"),
                    "stock" => $produit->getValue("p_stock"),
                    "categorie" => [
                        "id" => $idCategorie,
                        "libelle" => $categorie->getValue("ca_libelle")
                    ],
                    "format" => $arrayFormat,
                    "image" => $produit->get("p_ref_piecejointe_image")->getObjet()->get_url()
                ];
            }

            $arrayRetour[] = [
                "id_categorie" => $idCategorie,
                "libelle_categorie" => $categorie->getValue("ca_libelle"),
                "produits" => $arrayRetourProduits
            ];
        }

        $this->sortie["categories"] = $arrayRetour;
        $this->makeRetour(true,"","Chargement des produits avec succès");

        return true;
    }

}