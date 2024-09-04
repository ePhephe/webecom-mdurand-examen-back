<?php

/**
 * Classe new_password : classe du controller new_password
 * * Rôle : Procède à la définition du nouveau mot de passe et à la remise à zéro des informations de réinitialisation
 */

class new_password extends _controller {

    /**
     * Attributs
     */

    // Nom du controller
    protected $name = "NewPassword";
    // Liste des objets manipulés par le controller
    protected $objects = ["utilisateur" => ["update"]]; // ["objet1" => ["action"1,"action2"...],"objet2" => ["action"1,"action2"...]]

    // * Paramètres du controller attendus en entrée
    // * validator - Token de réinitialisation - GET - Requis
    // * selector - Clé sélecteur du token de réinitialisation - GET - Requis
    // * Informations pour définir le nouveau mot de passe (Login + Mdp + Confirmation) - Issu du formulaire de définition - POST - Requis
    protected $paramEntree = [ // ["nom_param1"=>["method"=>"POST","required"=>true],"nom_param2"=>["method"=>"POST","required"=>false]]
        "validator"=>["method"=>"_GET","required"=>true],
        "selector"=>["method"=>"_GET","required"=>true],
        "form" => ["method" => "_POST", "required" => true]
    ];
    
    // Type de retour
    protected $typeRetour = "pages"; // json, fragments ou pages (défaut)
    // Nom du template
    protected $template = "form_new_password";
    // Tableau de paramètre du template
    protected $paramTemplate = [ // ["head" => ["title" => "", "metadescription" => "", "lang" => ""], "is_nav" => true, "is_footer" => true]
        "head" => [
            "title" => "Définition de votre nouveau mot de passe - Money",
            "metadescription" => "Money est un site qui permet de lancer et de soutenir des projets innovants. Pour accéder à votre compte, définissez votre nouveau mot de passe.",
            "lang" => "fr"
        ],
        "is_nav" => true,
        "is_footer" => false
    ];
    // Paramètres en sortie du controller
    protected $paramSortie = ["htmlFormulaireNewPassword"=>["required"=>true],"arrayResult"=>["required"=>false]]; // ["nom_param1"=>["required"=>true],"nom_param2"=>["required"=>false]]
    // Besoin d'être connecté
    protected $connected = false; // True par défaut

    /**
     * Exécution du rôle du controller
     *
     * @return boolean True si tout s'est bien passé, False si une erreur est survenu
     */
    public function execute(){
        // On prépare un objet de la classe user
        $nomObjet = $this->get("session")->getTableUser();
        $objUtilisateur = new $nomObjet();

        // On vérifie que les paramètres nécessaires à lancer la réinitialisation sont présents
        if(!parent::verifParams() || (!isset($this->parametres["login"]) || !isset($this->parametres["password"]) || !isset($this->parametres["confirmPassword"]))){
            // Sinon on redirige vers la page de connexion
            header("Location: ".$objUtilisateur->getURLFormulaire("formLogin")."?redirect=echecnewpassword");
            exit;
        }

        //On récupère les paramètres dans des variables
        $strSelector = $this->parametres["selector"];
        $strToken = $this->parametres["validator"];
        $strLogin = $this->parametres["login"];
        $strPassword = $this->parametres["password"];
        $strConfirmPassword = $this->parametres["confirmPassword"];

        //On vérifie que le token est bon
        $resultat = $objUtilisateur->verifTokenReiniPassword($strLogin,$strSelector,$strToken);
        //Si le token n'est pas valide on affiche une erreur
        if($resultat === false){
            //On met en forme une erreur
            $this->sortie["arrayResult"]["type_message"] = "erreur";
            $this->sortie["arrayResult"]["message"] = "Nous n'avons pas réussi à définir votre nouveau mot de passe, refaites une demande de mot de passe oublié.";
            //On récupère le formulaire de réinitialisation du mot de passe depuis un objet utilisateur
            $this->sortie["htmlFormulaireNewPassword"] = $objUtilisateur->formulaireNewPassword(
                $strSelector,
                $strToken
            );

            return false;
        }
        else {
            //On essaye de mettre à jour le mot de passe
            $resultat = $objUtilisateur->updateNewPassword($strPassword,$strConfirmPassword);
            //Si le résultat n'est pas bon
            if($resultat === false){
                //On met en forme une erreur
                $this->sortie["arrayResult"]["type_message"] = "erreur";
                $this->sortie["arrayResult"]["message"] = "Nous n'avons pas réussi à définir votre nouveau mot de passe.";
                //On récupère le formulaire de réinitialisation du mot de passe depuis un objet utilisateur
                $this->sortie["htmlFormulaireNewPassword"] = $objUtilisateur->formulaireNewPassword(
                    $strSelector,
                    $strToken
                );

                return false;
            }
        }

        // On redirige vers lap age de connexion avec la raison de la redirection
        header("Location: ".$objUtilisateur->getURLFormulaire("formLogin")."?redirect=password");
    }

}
