<?php

/**
 * Classe livrer_commande : classe du controller livrer_commande
 * * Rôle : Passe le statut de la commande en Livrée
 */

class livrer_commande extends _controller {

    /**
     * Attributs
     */

    // Nom du controller
    protected $name = "LivrerCommande";
    // Liste des objets manipulés par le controller
    protected $objects = ["commande" => ["livrer"]]; // ["objet1" => ["action"1,"action2"...],"objet2" => ["action"1,"action2"...]]

    // * Paramètres du controller attendus en entrée
    // * idcommande - Identifiant de la commande dont il faut changer le statut - GET - Requis
    protected $paramEntree = [// ["nom_param1"=>["method"=>"POST","required"=>true],"nom_param2"=>["method"=>"POST","required"=>false]]
        "idcommande" => ["method" => "_GET", "required" => true]
    ];
    
    // Type de retour
    protected $typeRetour = "json"; // json, fragments ou pages (défaut)
    // Nom du template
    protected $template = "";
    // Tableau de paramètre du template
    protected $paramTemplate = [ // ["head" => ["title" => "", "metadescription" => "", "lang" => ""], "is_nav" => true, "is_footer" => true]
    ];
    // Paramètres en sortie du controller
    protected $paramSortie = [ // ["nom_param1"=>["required"=>true],"nom_param2"=>["required"=>false]]
        "commande" => ["required" => false],
        "permission_livrer" => ["required" => false],
        "permission_preparer" => ["required" => false],
        "permission_supprimer" => ["required" => false],
        "permission_creer" => ["required" => false]
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
            // On instancie un objet produit
            $objCommande = new commande($this->parametres["idcommande"]);

            // Il faut que la commande soit prête
            if($objCommande->getValue("c_statut") === "P") {
                // On indique son statut Prête
                $objCommande->set("c_statut","L");
                // On met à jour la date de modification et de livraison
                $dateModification = new DateTime();
                $objCommande->set("c_datetime_modification",$dateModification->format("Y-m-d H:i:s"));
                $objCommande->set("c_datetime_livraison",$dateModification->format("Y-m-d H:i:s"));

                // On met à jour la commande
                $resultat = $objCommande->update();

                // Si la modification a fonctionné
                if($resultat === true) {
                    // On prépare le retour du succès avec la commande
                    $this->sortie["commande"] = $objCommande->getToArray();
                    $this->sortie["commande"]["total"] = $objCommande->getTotalCommande();
                    // On récupère les permission pour l'affichage
                    $this->sortie["permission_livrer"] = $this->permission->verifPermission("commande","livrer");
                    $this->sortie["permission_preparer"] = $this->permission->verifPermission("commande","preparer");
                    $this->sortie["permission_supprimer"] = $this->permission->verifPermission("commande","delete");
                    $this->sortie["permission_creer"] = $this->permission->verifPermission("commande","create");
                    $this->makeRetour(true,"succes","La commande a été passée livrée avec succès !");
                }
                else {
                    //On met en forme une erreur si les paramètres ne sont pas présents
                    $this->makeRetour(false,"error","La commande n'a pas pu être passée à livrée !");
                }
            }
            else {
                //On met en forme une erreur si les paramètres ne sont pas présents
                $this->makeRetour(false,"error","La commande n'a pas pu être passée à livrée !");
            }
        }
        else {
            //On met en forme une erreur si les paramètres ne sont pas présents
            $this->makeRetour(false,"error","Paramètres manquants");
        }
    }
}
