<?php

/**
 *
 * Attributs disponibles
 *
 * Méthodes disponibles
 * @method moinsStock() Retire la quantité indiquée du stock
 *
 */

/**
 * Classe produit : classe de gestion des produits
 */

class produit extends _model {

    /**
     * Attributs
     */

    // Nom de la table dans la BDD
    protected $table = "produit";
    // Abbréviation de la table dans la BDD
    protected $abrev_table = "p";
    
    /**
     * Méthodes
     */

    /**
     * Retire la quantité indiquée du stock
     *
     * @param integer $quantite Quantité à retirer du stock
     * @return boolean True si tout s'est bien passé, sinon false
     */
    public function moinsStock($quantite) {
        // On vérifie que l'objet est bien chargé
        if($this->is()) {
            // On récupère la quantité actuelle
            $currentQte = $this->getValue("p_stock");
            // On calcule la nouvelle quantité
            $newQte = $currentQte - $quantite;

            // Si la nouvelle quantité est inférieur à 5 (pour avoir un peu de marge), on passe le produit en indisponible
            if($newQte < 5) {
                $this->set("p_isdispo",0);
            }

            // On met la nouvelle quantité
            $this->set("p_stock",$newQte);

            // On sauvegarde le produit
            return  $this->update();
        }

        return false;
    }
    
}