<?php

/**
 * Classe se_connecter : classe du controller se_connecter
 * * Rôle : Vérifie la connexion au compte
 */

class se_connecter extends _controller {

    /**
     * Attributs
     */

    // Nom du controller
    protected $name = "SeConnecter";
    // Liste des objets manipulés par le controller
    protected $objects = []; // ["objet1" => ["action"1,"action2"...],"objet2" => ["action"1,"action2"...]]

    // * Paramètres du controller attendus en entrée
    // * login - Identifiant issu du formulaire de connexion - POST - Requis
    // * password - Mot de passe issu du formulaire de connexion - POST - Requis
    protected $paramEntree = [ // ["nom_param1"=>["method"=>"POST","required"=>true],"nom_param2"=>["method"=>"POST","required"=>false]]
        "login" => ["method" => "POST", "required" => true],
        "password" => ["method" => "POST", "required" => true]
    ];
    
    // Type de retour
    protected $typeRetour = "pages"; // json, fragments ou pages (défaut)
    // Nom du template
    protected $template = "form_connexion";
    // Tableau de paramètre du template
    protected $paramTemplate = [ // ["head" => ["title" => "", "metadescription" => "", "lang" => ""], "is_nav" => true, "is_footer" => true]
        "head" => [
            "title" => "Se connecter - Wakdo",
            "metadescription" => "Interface de gestion interne des produits et des commandes Wakdo",
            "lang" => "fr"
        ],
        "is_nav" => false,
        "is_footer" => false
    ];
    // Paramètres en sortie du controller
    protected $paramSortie = ["htmlFormulaireConnexion"=>["required"=>true],"arrayResult"=>["required"=>false]]; // ["nom_param1"=>["required"=>true],"nom_param2"=>["required"=>false]]
    // Besoin d'être connecté
    protected $connected = false; // True par défaut

    /**
     * Exécution du rôle du controller
     *
     * @return boolean True si tout s'est bien passé, False si une erreur est survenu
     */
    public function execute(){
        //Fonction à surchargée dans la classe fille
        //On récupère le formulaire de connexion depuis un objet utilisateur
        $nomObjet = $this->get("session")->getTableUser();
        $objUtilisateur = new $nomObjet();

        // On vérifie que l'on a bien les paramètres nécessaires
        if(!parent::verifParams()) {
            //On met en forme une erreur si les paramètres ne sont pas présents
            $this->sortie["arrayResult"]["type_message"] = "erreur";
            $this->sortie["arrayResult"]["message"] = "Merci de renseigner correctement vos informations de connexion.";
            //On récupère le formulaire de connexion depuis un objet utilisateur
            $this->sortie["htmlFormulaireConnexion"] = $objUtilisateur->formulaireConnexion();

            return false;
        }
        
        // On récupère nos paramètres dans des variables
        $strLogin = $this->parametres["login"];
        $strPassword = $this->parametres["password"];

        //On appelle la méthode pour vérifier la connexion
        $resultatConnexion = $objUtilisateur->connexion($strLogin,$strPassword);

        //Sinon l'utilisateur n'est pas connecté
        if($resultatConnexion === false) {
            //On met en forme une erreur si les paramètres ne sont pas présents
            $this->sortie["arrayResult"]["type_message"] = "erreur";
            $this->sortie["arrayResult"]["message"] = "Vos informations de connexion sont incorrectes.";
            //On récupère le formulaire de connexion depuis un objet utilisateur
            $this->sortie["htmlFormulaireConnexion"] = $objUtilisateur->formulaireConnexion();

            return false;
        }

        //On connecte la session
        $this->session->connect($objUtilisateur->id());

        //On redirige l'utilisateur vers la page d'accueil
        header("Location:afficher_accueil.php");

        return true;
    }

}
