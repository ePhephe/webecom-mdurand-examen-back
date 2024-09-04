<?php

/**
 * Classe finaliser_commande : classe du controller finaliser_commande
 * * Rôle : Finalise la commande avec ses produits
 */

class finaliser_commande extends _controller {

    /**
     * Attributs
     */

    // Nom du controller
    protected $name = "FinaliserCommande";
    // Liste des objets manipulés par le controller
    protected $objects = ["commande" => ["create"]]; // ["objet1" => ["action"1,"action2"...],"objet2" => ["action"1,"action2"...]]

    // * Paramètres du controller attendus en entrée
    // * idcommande - Identifiant de la commande - json - Requis
    // * produits - Informations sur les produits - json - Requis
    protected $paramEntree = [// ["nom_param1"=>["method"=>"POST","required"=>true],"nom_param2"=>["method"=>"POST","required"=>false]]
        "idcommande" => ["method" => "json", "required" => true],
        "produits" => ["method" => "json", "required" => true]
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

            if($objCommande->getValue("c_statut") === "C") {
                // On parcourt les produits et on les ajoute
                foreach ($this->parametres["produits"] as $produit) {
                    $objCdeProduit = new commande_produit();
                    $objCdeProduit->addProduit($this->parametres["idcommande"],$produit);
                }

                // On indique son statut A préparer
                $objCommande->set("c_statut","AP");
                // On indique les dates de création et modification (identique à la création)
                $dateModification = new DateTime();
                $objCommande->set("c_datetime_modification",$dateModification->format("Y-m-d H:i:s"));
                // On met à jour l'heure de livraison prévue une fois la commande finalisée
                $dateLivraison = new DateTime();
                $dateLivraison->add(new DateInterval('PT5M'));
                $objCommande->set("c_datetime_livraison",$dateLivraison->format("Y-m-d H:i:s"));

                // On met à jour la commande
                $resultat = $objCommande->update();

                // Si la modification a fonctionné
                if($resultat === true) {
                    // On prépare le retour du succès
                    $this->makeRetour(true,"succes","La commande a été finalisée avec succès !");
                }
                else {
                    //On met en forme une erreur
                    $this->makeRetour(false,"error","La commande n'a pas pu être finalisée !");
                }
            }
            else {
                //On met en forme une erreur si la commande n'est pas dans le bon statut
                $this->makeRetour(false,"error","La commande n'est pas en cours de création");
            }
        }
        else {
            //On met en forme une erreur si les paramètres ne sont pas présents
            $this->makeRetour(false,"error","La commande n'a pas pu être finalisée !");
        }
    }
}
