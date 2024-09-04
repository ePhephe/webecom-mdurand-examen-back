<?php

/**
 * Classe afficher_form_commande_produit : classe du controller afficher_form_commande_produit
 * * Rôle : Prépare et affiche le formulaire de l'ajout d'un produit à une commande
 */

class afficher_form_commande_produit extends _controller {

    /**
     * Attributs
     */

    // Nom du controller
    protected $name = "AfficherFormCommandeProduit";
    // Liste des objets manipulés par le controller
    protected $objects = ["commande" => ["create","update"]]; // ["objet1" => ["action"1,"action2"...],"objet2" => ["action"1,"action2"...]]

    // * Paramètres du controller attendus en entrée
    // * idproduit - Identifiant du produit que l'on veut ajouter - GET - Requis
    protected $paramEntree = [ // ["nom_param1"=>["method"=>"POST","required"=>true],"nom_param2"=>["method"=>"POST","required"=>false]]
        "idproduit" => ["method" => "_GET", "required" => true]
    ];
    
    // Type de retour
    protected $typeRetour = "fragments"; // json, fragments ou pages (défaut)
    // Nom du template
    protected $template = "form_commande_produit";
    // Tableau de paramètre du template
    protected $paramTemplate = [ // ["head" => ["title" => "", "metadescription" => "", "lang" => ""], "is_nav" => true, "is_footer" => true]
    ];
    // Paramètres en sortie du controller
    protected $paramSortie = [ // ["nom_param1"=>["required"=>true],"nom_param2"=>["required"=>false]]
        "htmlFormulaireCdeProduit"=>["required"=>true],
        "produit"=>["required"=>true]
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

        // S'il n'y a pas de paramètre idproduit, on retourne false
        if(!isset($this->parametres["idproduit"])) { return false; }

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
            //On met en forme une erreur si les paramètres ne sont pas présents
            $this->makeRetour(false,"echec","Les paramètres nécessaires ne sont pas présents !");
            return false;
        }

        // On prépare un objet de la classe menu
        $objCommandeProduit = new commande_produit();
        // On charge un objet de la classe produit
        $objProduit = new produit($this->parametres["idproduit"]);

        // On récupère le formulaire, en précisant qu'on ne prend les formats que de la catégorie du produit
        $this->sortie["htmlFormulaireCdeProduit"] = $objCommandeProduit->getFormulaire(
            "create",
            false,
            true,
            [],
            [],
            [
                "cp_ref_format"=>[
                    "filtres"=>[
                        [
                            "champ" => "f_ref_categorie",
                            "valeur" => $objProduit->getValue("p_ref_categorie"),
                            "operateur" => "=",
                            "table" => "format"
                        ]
                    ]
                ]
            ]
        );
        $this->sortie["produit"] = $objProduit;

        // On met en forme le retour avec succès
        $this->makeRetour(true,"succes","Chargement réussi");

        return true;
    }

}
