<?php

/**
 * Classe creer_commande : classe du controller creer_commande
 * * Rôle : Crée un nouvelle commande dans la base de données
 */

class creer_commande extends _controller {

    /**
     * Attributs
     */

    // Nom du controller
    protected $name = "CreerCommande";
    // Liste des objets manipulés par le controller
    protected $objects = ["commande" => ["create"]]; // ["objet1" => ["action"1,"action2"...],"objet2" => ["action"1,"action2"...]]

    // * Paramètres du controller attendus en entrée
    // * Informations de la commande - Issu du formulaire correspondant - POST - Requis
    protected $paramEntree = ["form" => ["method" => "_POST", "required" => true]]; // ["nom_param1"=>["method"=>"POST","required"=>true],"nom_param2"=>["method"=>"POST","required"=>false]]
    
    // Type de retour
    protected $typeRetour = "pages"; // json, fragments ou pages (défaut)
    // Nom du template
    protected $template = "form_commande";
    // Tableau de paramètre du template
    protected $paramTemplate = [ // ["head" => ["title" => "", "metadescription" => "", "lang" => ""], "is_nav" => true, "is_footer" => true]
        "head" => [
            "title" => "Gestion d'une commande - Wakdo",
            "metadescription" => "Gestion des commandes de Wakdo.",
            "lang" => "fr"
        ],
        "is_nav" => true,
        "is_footer" => true
    ];
    // Paramètres en sortie du controller
    protected $paramSortie = [ // ["nom_param1"=>["required"=>true],"nom_param2"=>["required"=>false]]
        "htmlFormulaire"=>["required"=>true],
        "arrayResult"=>["required"=>false],
        "action"=>["required"=>false],
        "commande"=>["required"=>false]
    ];
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
            // On vérifie la complétion du numéro de chevalet en fonction du type de service
            if(isset($this->parametres["form"]["c_type_service"]) && $this->parametres["form"]["c_type_service"] === "SP") {
                if(empty($this->parametres["form"]["c_num_chevalet"])){
                    return false;
                }
                else {
                    // On vérifie si la valeur est un entier
                    if (filter_var($this->parametres["form"]["c_num_chevalet"], FILTER_VALIDATE_INT) !== false) {
                        // On vérifie si il est compris entre 1 et 999 inclus
                        if ($this->parametres["form"]["c_num_chevalet"] < 1 && $this->parametres["form"]["c_num_chevalet"] > 999) {
                            return false;
                        }
                    }
                    else {
                        return false;
                    }
                }
            }

            // On vérifie que l'on a au moins les champs essentiels
            return !empty($this->parametres["form"]["c_type_service"]) &&
            !empty($this->parametres["form"]["c_commentaire"]);
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
        // On instancie un objet produit
        $objCommande = new commande();
        // On commence par appeler la vérification des paramètres
        if($this->verifParams()) {
            // On vérifie que les paramètres sont OK
            if($objCommande->verifParamsFormulaire($this->parametres["form"])){
                // Avant de créer la commande
                // On crée son numéro
                $objCommande->set("c_num_cde",rand(10000, 99999));
                // On créé sa référence
                $objCommande->set("c_ref_cde",$objCommande->getValue("c_type_service").rand(1000, 9999));
                // On indique son statut Créée
                $objCommande->set("c_statut","C");
                // On indique les dates de création et modification (identique à la création)
                $dateCreation = new DateTime();
                $objCommande->set("c_datetime_creation",$dateCreation->format("Y-m-d H:i:s"));
                $objCommande->set("c_datetime_modification",$dateCreation->format("Y-m-d H:i:s"));
                // On indique l'heure de livraison prévue
                $dateLivraison = new DateTime();
                $dateLivraison->add(new DateInterval('PT5M'));
                $objCommande->set("c_datetime_livraison",$dateLivraison->format("Y-m-d H:i:s"));


                // On réalise la mise à jour dans la base de données
                $resultat = $objCommande->insert();
                
                // Si la modification a fonctionné
                if($resultat === true) {
                    // On dirige vers la construction du contenu de la commande
                    header("Location: afficher_commande_construction.php?idcommande=".$objCommande->id());
                }
                else {
                    //On met en forme une erreur si les paramètres ne sont pas présents
                    $this->sortie["arrayResult"]["type_message"] = "erreur";
                    $this->sortie["arrayResult"]["message"] = "Votre commande n'a pas pu être créé.";
                }
            }
            else {
                //On met en forme une erreur si les paramètres ne sont pas présents
                $this->sortie["arrayResult"]["type_message"] = "erreur";
                $this->sortie["arrayResult"]["message"] = "(2) Les paramètres fournis ne sont pas corrects.";
            }
        }
        else {
            //On met en forme une erreur si les paramètres ne sont pas présents
            $this->sortie["arrayResult"]["type_message"] = "erreur";
            $this->sortie["arrayResult"]["message"] = "(1) Les paramètres fournis ne sont pas corrects.";
        }

        // On récupère l'action
        $this->sortie["action"] = "create";
        // On récupère l'objet produit
        $this->sortie["commande"] = $objCommande;
        // On récupère le formulaire
        $this->sortie["htmlFormulaire"] = $objCommande->getFormulaire(
            "create",
            false,
            true,
            [],
            []
        );
    }
}
