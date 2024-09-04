<?php

/**
 * Classe supprimer_produit : classe du controller supprimer_produit
 * * Rôle : Supprimer un produit existant dans la base de données
 */

class supprimer_produit extends _controller {

    /**
     * Attributs
     */

    // Nom du controller
    protected $name = "SupprimerProduit";
    // Liste des objets manipulés par le controller
    protected $objects = ["produit" => ["delete"]]; // ["objet1" => ["action"1,"action2"...],"objet2" => ["action"1,"action2"...]]

    // * Paramètres du controller attendus en entrée
    // * id - Identifiant du produit - GET - Requis
    protected $paramEntree = [ // ["nom_param1"=>["method"=>"POST","required"=>true],"nom_param2"=>["method"=>"POST","required"=>false]]
        "id" => ["method" => "_GET", "required" => true],
    ];
    
    // Type de retour
    protected $typeRetour = "json"; // json, fragment ou template (défaut)
    // Nom du template
    protected $template = "";
    // Tableau de paramètre du template
    protected $paramTemplate = []; // ["head" => ["title" => "", "metadescription" => "", "lang" => ""], "is_nav" => true, "is_footer" => true]
    // Paramètres en sortie du controller
    protected $paramSortie = []; // ["nom_param1"=>["required"=>true],"nom_param2"=>["required"=>false]]
    // Besoin d'être connecté
    protected $connected = true; // True par défaut

    /**
     * Exécution du rôle du controller
     *
     * @return boolean True si tout s'est bien passé, False si une erreur est survenu
     */
    public function execute() {
        // On commence par appeler la vérification des paramètres
        if (!parent::verifParams()) {
            $this->makeRetour(false, "param", "(1) Les paramètres fournis ne sont pas corrects.");
        }
        // On instancie l'objet du produit passé en paramètre
        $objProduit = new produit($this->parametres["id"]);

        // On regarde sur le produit est un menu
        if($objProduit->getValue("p_ref_categorie") == 1) {
            // Dans ce cas il faut remettre à zéro sa composition avant de le supprimer
            $objMenu = new menu();
            $objMenu->resetComposition($objProduit->id());
        }

        // On réalise la mise à jour dans la base de données
        $resultat = $objProduit->delete();

        // Si la modification a fonctionné
        if ($resultat === true) {
            // On prépare le retour
            $this->makeRetour(true, "succes", "Le produit a été supprimé avec succès !");
        } else {
            // Sinon on formate une erreur
            $this->makeRetour(false, "echec", "La suppression du produit n'a pas pu être effectuée.");
        }
    }

}
