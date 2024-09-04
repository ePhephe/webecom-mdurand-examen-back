<?php

/**
 * Classe supprimer_utilisateur : classe du controller supprimer_utilisateur
 * * Rôle : Supprimer un utilisateur existant dans la base de données
 */

class supprimer_utilisateur extends _controller {

    /**
     * Attributs
     */

    // Nom du controller
    protected $name = "SupprimerUtilisateur";
    // Liste des objets manipulés par le controller
    protected $objects = ["utilisateur" => ["update"]]; // ["objet1" => ["action"1,"action2"...],"objet2" => ["action"1,"action2"...]]

    // * Paramètres du controller attendus en entrée
    // * id - Identifiant de l'utilisateur - GET - Requis
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
            // On redirige vers la liste des utilisateurs si on a pas les paramètres nécessaire
            header("Location:lister_utilisateurs.php");
            exit;
        }
        // On instancie l'objet de l'utilisateur passé en paramètre
        $nomObjet = $this->session->getTableUser();
        $objUtilisateur = new $nomObjet($this->parametres["id"]);

        // On met à jour son statut et sa date de suppression
        $objUtilisateur->set("u_statut","D");

        // On réalise la mise à jour dans la base de données
        $objUtilisateur->update();

        // On redirige vers la liste des utilisateurs
        header("Location:lister_utilisateurs.php");
        exit;
    }

}
