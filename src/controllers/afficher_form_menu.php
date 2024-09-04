<?php

/**
 * Classe afficher_form_menu : classe du controller afficher_form_menu
 * * Rôle : Prépare et affiche le formulaire de l'ajout d'un menu à une commande
 */

class afficher_form_menu extends _controller {

    /**
     * Attributs
     */

    // Nom du controller
    protected $name = "AfficherFormMenu";
    // Liste des objets manipulés par le controller
    protected $objects = ["commande" => ["create","update"]]; // ["objet1" => ["action"1,"action2"...],"objet2" => ["action"1,"action2"...]]

    // * Paramètres du controller attendus en entrée
    // * idmenu - Identifiant du menu que l'on veut ajouter - GET - Requis
    protected $paramEntree = [ // ["nom_param1"=>["method"=>"POST","required"=>true],"nom_param2"=>["method"=>"POST","required"=>false]]
        "idmenu" => ["method" => "_GET", "required" => true]
    ];
    
    // Type de retour
    protected $typeRetour = "fragments"; // json, fragments ou pages (défaut)
    // Nom du template
    protected $template = "form_menu";
    // Tableau de paramètre du template
    protected $paramTemplate = [ // ["head" => ["title" => "", "metadescription" => "", "lang" => ""], "is_nav" => true, "is_footer" => true]
    ];
    // Paramètres en sortie du controller
    protected $paramSortie = [ // ["nom_param1"=>["required"=>true],"nom_param2"=>["required"=>false]]
        "arrayCompositionChoix"=>["required"=>true],
        "arrayListeFormatMenu"=>["required"=>true],
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

        // S'il n'y a pas de paramètre idmenu, on retourne false
        if(!isset($this->parametres["idmenu"])) { return false; }

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
        $objMenu = new menu();
        // On charge un objet de la classe produit
        $objProduitMenu = new produit($this->parametres["idmenu"]);
        // On prépare un objet de la classe format
        $objFormat = new format();

        // On récupère la liste de la composition de notre menu avec les produits permis pour chaque composition
        $arrayCompositionChoix = $objMenu->listCompositionChoix($this->parametres["idmenu"]);

        // On a besoin de recupère la liste des formats possibles pour la catégorie
        $arrayListeFormatMenu = $objFormat->listFormatCategorie($objProduitMenu->getValue("p_ref_categorie"));

        $this->sortie["arrayCompositionChoix"] = $arrayCompositionChoix;
        $this->sortie["arrayListeFormatMenu"] = $arrayListeFormatMenu;
        $this->sortie["produit"] = $objProduitMenu;

        // On met en forme le retour avec succès
        $this->makeRetour(true,"succes","Chargement réussi");

        return true;
    }

}
