<?php

/**
 * Classe afficher_form_utilisateur : classe du controller afficher_form_utilisateur
 * * Rôle : Prépare et affiche le formulaire de gestion d'un utilisateur
 */

class afficher_form_utilisateur extends _controller {

    /**
     * Attributs
     */

    // Nom du controller
    protected $name = "AfficherFormUtilisateur";
    // Liste des objets manipulés par le controller
    protected $objects = ["utilisateur" => ["read"]]; // ["objet1" => ["action"1,"action2"...],"objet2" => ["action"1,"action2"...]]

    // * Paramètres du controller attendus en entrée
    // * id - Identifiant de l'utilisateur sur lequel agir - GET - Facultatif
    // * action - Action à réaliser sur l'objet indiqué - GET - Facultatif
    protected $paramEntree = [ // ["nom_param1"=>["method"=>"POST","required"=>true],"nom_param2"=>["method"=>"POST","required"=>false]]
        "id" => ["method" => "_GET", "required" => false],
        "action" => ["method" => "_GET", "required" => false]
    ];
    
    // Type de retour
    protected $typeRetour = "pages"; // json, fragments ou pages (défaut)
    // Nom du template
    protected $template = "form_utilisateur";
    // Tableau de paramètre du template
    protected $paramTemplate = [ // ["head" => ["title" => "", "metadescription" => "", "lang" => ""], "is_nav" => true, "is_footer" => true]
        "head" => [
            "title" => "Gestion de l'utilisateur - Wakdo",
            "metadescription" => "Gérer les comptes de vos utilisateurs.",
            "lang" => "fr"
        ],
        "is_nav" => true,
        "is_footer" => true
    ];
    // Paramètres en sortie du controller
    protected $paramSortie = ["htmlFormulaire"=>["required"=>true],"arrayResult"=>["required"=>false]]; // ["nom_param1"=>["required"=>true],"nom_param2"=>["required"=>false]]
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

        // S'il n'y a pas de paramètre id, on l'initialise à null
        if(!isset($this->parametres["id"])) { $this->parametres["id"] = null; }

        // S'il n'y a pas de paramètre action, on l'initialise à read
        // Et on initialise aussi le json, si on est en update ou delete, le formulaire sera en ajax
        if(!isset($this->parametres["action"])) {
            $this->parametres["action"] = "read";
            $this->parametres["json"] = false;
        }
        else {
            $this->parametres["json"] = false;
        }

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
            //On redirige l'utilisateur vers la liste des utilisateurs
            header("Location:lister_utilisateurs.php");
            exit;
        }

        // On prépare un objet de la classe user
        $nomObjet = $this->get("session")->getTableUser();
        $objUtilisateur = new $nomObjet($this->parametres["id"]);

        // On génère le formulaire
        if($this->parametres["action"] === "update" && $this->parametres["id"] === $this->session->id()) {
            // Dans le cas où on est mise à jour et que l'id de l'utilisateur est le même que l'utilisateur connecté, on est en màj de compte
            $this->sortie["htmlFormulaire"] = $objUtilisateur->getFormulaire(
                $this->parametres["action"],
                $this->parametres["json"],
                true,
                [],
                // Donc on ne permet pas d'afficher le rôle
                ["u_role_user" => ["display" => "none"]]
            );
        }
        else{
            // Dans les autres cas on récupère le formulaire tel quel
            $this->sortie["htmlFormulaire"] = $objUtilisateur->getFormulaire(
                $this->parametres["action"],
                $this->parametres["json"]
            );
        }

        return true;
    }
}
