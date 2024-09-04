<?php

/**
 * Classe modifier_utilisateur : classe du controller modifier_utilisateur
 * * Rôle : Modifie un utilisateur existant dans la base de données
 */

class modifier_utilisateur extends _controller {

    /**
     * Attributs
     */

    // Nom du controller
    protected $name = "ModifierUtilisateur";
    // Liste des objets manipulés par le controller
    protected $objects = ["utilisateur" => ["update"]]; // ["objet1" => ["action"1,"action2"...],"objet2" => ["action"1,"action2"...]]

    // * Paramètres du controller attendus en entrée
    // * id - Identifiant de l'utilisateur - GET - Requis
    // * Informations de l'utilisateur - Issu du formulaire correspondant - POST - Requis
    protected $paramEntree = [ // ["nom_param1"=>["method"=>"POST","required"=>true],"nom_param2"=>["method"=>"POST","required"=>false]]
        "id" => ["method" => "_GET", "required" => true],
        "form" => ["method" => "_POST", "required" => true]
    ];
    
    // Type de retour
    protected $typeRetour = "pages"; // json, fragment ou template (défaut)
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
    protected $paramSortie = [// ["nom_param1"=>["required"=>true],"nom_param2"=>["required"=>false]]
        "arrayResult"=>["required"=>false],
        "htmlFormulaire"=>["required"=>true]
    ]; 
    // Besoin d'être connecté
    protected $connected = true; // True par défaut

    /**
     * Exécution du rôle du controller
     *
     * @return boolean True si tout s'est bien passé, False si une erreur est survenu
     */
    public function execute(){
        // On commence par appeler la vérification des paramètres
        if(parent::verifParams()) {
            // On instancie l'objet de l'utilisateur passé en paramètre
            $nomObjet = $this->session->getTableUser();
            $objUtilisateur = new $nomObjet($this->parametres["id"]);

            // On commence par appeler la vérification des paramètres
            if($objUtilisateur->verifParamsFormulaire($this->parametres["form"])){
                // On réalise la mise à jour dans la base de données
                $resultat = $objUtilisateur->update();

                // Si la modification a fonctionné
                if($resultat === true) {
                    // On redirige vers la liste des utilisateurs
                    header("Location:lister_utilisateurs.php");
                    exit;
                }
                else {
                    $this->sortie["arrayResult"]["type_message"] = "erreur";
                    $this->sortie["arrayResult"]["message"] = "L'utilisateur n'a pas pu être mis à jour.";
                    //On récupère le formulaire de connexion depuis un objet utilisateur
                    $this->sortie["htmlFormulaire"] = $objUtilisateur->getFormulaire(
                        "update",
                        false
                    );
                }
            }
            else {
                $this->sortie["arrayResult"]["type_message"] = "erreur";
                $this->sortie["arrayResult"]["message"] = "Les informations ne sont pas correctes";
                //On récupère le formulaire de connexion depuis un objet utilisateur
                $this->sortie["htmlFormulaire"] = $objUtilisateur->getFormulaire(
                    "update",
                    false
                );
            }
        }
        else {
            // On redirige vers la liste des utilisateurs si on a pas les paramètres nécessaire
            header("Location:lister_utilisateurs.php");
            exit;
        }
    }
}
