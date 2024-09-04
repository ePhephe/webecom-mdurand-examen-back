<?php

/**
 * 
 * Attributs disponibles
 * 
 * Méthodes disponibles
 * @method listProduits() Récupère la liste des produits présents dans la commande
 * @method getTotalCommande() Calcule et retourne le total de la commande
 * @method setCommandePrete() Passe la commande dans l'état Prête
 * @method setCommandeLivree() Passe la commande dans l'état Livrée
 * 
 */

/**
 * Classe commande : classe de gestion des commandes
 */

class commande extends _model {

    /**
     * Attributs
     */

    // Nom de la table dans la BDD
    protected $table = "commande";
    // Abbréviation de la table dans la BDD
    protected $abrev_table = "c";
    
    /**
     * Méthodes
     */

    /**
     * Récupère la liste des produits présents dans la commande
     *
     * @return array Tableau d'objets des produits de la commande
     */
    public function listProduits(){
        // On instancie un objet commande_produit
        $objCdeProduit = new commande_produit();

        return $objCdeProduit->list(
            [],
            [
                [
                    "champ" => "cp_ref_commande",
                    "valeur" => $this->id(),
                    "operateur" => "=",
                    "table" => "commande_produit"
                ]
            ]
        );
    }

    /**
     * Calcule et retourne le total de la commande
     *
     * @return float Total de la commande en €
     */
    public function getTotalCommande(){
        // On récupère la liste des produits de la commande
        $arrayProduits = $this->listProduits();

        // On initialise un total à 0
        $totalCde = 0;
        // On parcourt les produits
        foreach ($arrayProduits as $produitCde) {
            //On récupère les objets nécessaires au calcul : produit, format
            $objProduit = $produitCde->get("cp_ref_produit")->getObjet();
            $objFormat = $produitCde->get("cp_ref_format")->getObjet();

            // Si on a une référence de menu, on ne compte pas le produit (inclut dans le menu)
            if(!is_null($produitCde->getValue("cp_ref_produit_menu"))){
                $totalCde += $produitCde->getValue("cp_quantite")*($objProduit->getValue("p_prix")+$objFormat->getValue("f_format_prix"));
            }
        }

        return $totalCde;
    }

    /**
     * Passe la commande dans l'état Prête
     */
    public function setCommandePrete($idUser){
        // On met l'état prête
        $this->set("c_statut","P");
        // On met l'utilisateur qui a préparée la commande
        $this->set("c_ref_user_preparation",$idUser);
        // On met à jour la date de preparation et la date de modification
        $dateModification = new DateTime();
        $this->set("c_datetime_modification",$dateModification->format("Y-m-d H:i:s"));
        $this->set("c_datetime_preparation",$dateModification->format("Y-m-d H:i:s"));
        
        // On met à jour la commande
        return $this->update();
    }

    /**
     * Passe la commande dans l'état Livrée
     */
    public function setCommandeLivree($idUser){
        // On met l'état livrée
        $this->set("c_statut","L");
        // On met l'utilisateur qui a livrée la commande
        $this->set("c_ref_user_livraison",$idUser);
        // On met à jour la date de preparation et la date de modification
        $dateModification = new DateTime();
        $this->set("c_datetime_modification",$dateModification->format("Y-m-d H:i:s"));
        $this->set("c_datetime_livraison",$dateModification->format("Y-m-d H:i:s"));
        
        // On met à jour la commande
        return $this->update();
    }
    
}