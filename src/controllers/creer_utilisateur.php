<?php

/**
 * Classe creer_utilisateur : classe du controller creer_utilisateur
 * * Rôle : Crée un nouvel utilisateur dans la base de données
 */

class creer_utilisateur extends _controller {

    /**
     * Attributs
     */

    // Nom du controller
    protected $name = "CreerUtilisateur";
    // Liste des objets manipulés par le controller
    protected $objects = ["utilisateur" => ["create"]]; // ["objet1" => ["action"1,"action2"...],"objet2" => ["action"1,"action2"...]]

    // * Paramètres du controller attendus en entrée
    // * Informations du projet - Issu du formulaire correspondant - POST - Requis
    protected $paramEntree = ["form" => ["method" => "_POST", "required" => true]]; // ["nom_param1"=>["method"=>"POST","required"=>true],"nom_param2"=>["method"=>"POST","required"=>false]]
    
    // Type de retour
    protected $typeRetour = "pages"; // json, fragments ou pages (défaut)
    // Nom du template
    protected $template = "form_utilisateur";
    // Tableau de paramètre du template
    protected $paramTemplate = [ // ["head" => ["title" => "", "metadescription" => "", "lang" => ""], "is_nav" => true, "is_footer" => true]
        "head" => [
            "title" => "Gestion de l'utilisateur - Wakdo",
            "metadescription" => "Gérer les comptes de vos utilisateur.",
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
        // On teste le verifParams parent, si c'est OK on continue
        if(parent::verifParams()) {
            // On met en place les paramètre du formulaire si jamais
            $this->parametres["action"] = "create";
            $this->parametres["json"] = false;
            // On vérifie que l'on a au moins les champs essentiels
            return !empty($this->parametres["form"]["u_nom"]) &&
            !empty($this->parametres["form"]["u_prenom"]) &&
            !empty($this->parametres["form"]["u_email"]) &&
            !empty($this->parametres["form"]["u_password"]) &&
            !empty($this->parametres["form"]["u_role_user"]);
        }
        else {
            return false;
        }
    }

    /**
     * Exécution du rôle du controller
     *
     * @return boolean True si tout s'est bien passé, False si une erreur est survenu
     */
    public function execute(){
        // On instancie un objet utilisateur
        $nomObjet = $this->session->getTableUser();
        $objUtilisateur = new $nomObjet();
        // On commence par appeler la vérification des paramètres
        if($this->verifParams()) {
            // On vérifie que les paramètres sont OK
            if($objUtilisateur->verifParamsFormulaire($this->parametres["form"])){
                // On met le bon statut et l'utilisateur créateur
                $objUtilisateur->set("u_statut","A");

                // On réalise l'insertion dans la base de données
                $resultat = $objUtilisateur->insert();
                // Si la création a fonctionné
                if($resultat === true) {
                    // On lance une réinitialisation pour que l'utilisateur créé son mot de passe
                    $objUtilisateur->demandeReiniPassword($objUtilisateur->getValue("u_email"));
                    // On redirige l'utilisateur vers la liste des utilisateurs
                    header("Location: lister_utilisateurs.php");
                    exit;
                }
                else {
                    //On met en forme une erreur si les paramètres ne sont pas présents
                    $this->sortie["arrayResult"]["type_message"] = "erreur";
                    $this->sortie["arrayResult"]["message"] = "L'utilisateur n'a pas pu être créé.";
                }
            }
            else {
                //On met en forme une erreur si les paramètres ne sont pas présents
                $this->sortie["arrayResult"]["type_message"] = "erreur";
                $this->sortie["arrayResult"]["message"] = "Les paramètres fournis ne sont pas corrects.";
            }
        }
        else {
            //On met en forme une erreur si les paramètres ne sont pas présents
            $this->sortie["arrayResult"]["type_message"] = "erreur";
            $this->sortie["arrayResult"]["message"] = "Les paramètres fournis ne sont pas corrects.";
        }

        //On récupère le formulaire de connexion depuis un objet utilisateur
        $this->sortie["htmlFormulaire"] = $objUtilisateur->getFormulaire(
            $this->parametres["action"],
            $this->parametres["json"]
        );
    }

}
