<?php

/**
 * Classe index : classe du controller index
 * * Rôle : Prépare et affiche le formulaire de connexion
 */

class index extends _controller {

    /**
     * Attributs
     */

    // Nom du controller
    protected $name = "Index";
    // Liste des objets manipulés par le controller
    protected $objects = []; // ["objet1" => ["action"1,"action2"...],"objet2" => ["action"1,"action2"...]]

    // * Paramètres du controller attendus en entrée
    // * redirect - Si l'utilisateur est arrivé sur la page en raison d'un redirection - GET - Facultatif
    protected $paramEntree = ["redirect" => ["method" => "_GET", "required" => false]]; // ["nom_param1"=>["method"=>"POST","required"=>true],"nom_param2"=>["method"=>"POST","required"=>false]]
    
    // Type de retour
    protected $typeRetour = "pages"; // json, fragment ou pages (défaut)
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
    function execute(){
        // On appelle la vérification des paramètres
        if(!parent::verifParams()) {
            return false;
        }

        //On récupère le paramètre redirect et on prépare le message d'erreur correspondant
        if (isset($this->parametres["redirect"])) {
            $this->sortie["arrayResult"]["type_message"] = "info";
            switch ($this->parametres["redirect"]) {
                case 'deconnect':
                    $this->sortie["arrayResult"]["message"] = "Vous avez été déconnecté car votre session a expirée.";
                    break;
                case 'notconnected':
                    $this->sortie["arrayResult"]["message"] = "Vous n'êtes pas connecté ! ";
                    break;
                case 'reini':
                    $this->sortie["arrayResult"]["message"] = "La demande de réinitialisation a été envoyé avec succès. Vous recevrez un e-mail dans les prochaines minutes.";
                    break;
                case 'password':
                    $this->sortie["arrayResult"]["message"] = "Votre mot de passe a été réinitialisé avec succès.";
                    break;
                case 'echecnewpassword':
                    $this->sortie["arrayResult"]["type_message"] = "erreur";
                    $this->sortie["arrayResult"]["message"] = "Nous n'avons pas les informations nécessaires à la définition de votre nouveau mot de passe.";
                    break;
                default:
                    break;
            }
        }

        //Si l'utilisateur est déjà connecté
        if($this->session->isConnected() === true) {
            //On le redirige vers la page d'accueil
            header("Location:afficher_accueil.php");
        }

        // On récupère un objet d'utilisateur via la session
        $objUser = $this->session->userConnected();

        // On appelle la méthode qui va générer le formulaire
        $this->sortie["htmlFormulaireConnexion"] = $objUser->formulaireConnexion();

        return true;
    }

}