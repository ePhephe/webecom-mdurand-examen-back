<?php

/**
 *
 * Attributs disponibles
 *
 * Méthodes disponibles
 * @method set_produits() Définit les produits possibles pour une ligne du menu
 * @method get_produits() Retourne les produits possibles pour une ligne du menu
 * @method listComposition() Retourne la composition d'un menu
 * @method resetComposition() Remet à zéro la composition d'un menu
 * @method listCompositionChoix() Retourne la composition d'un menu avec les possiblités pour chaque choix
 * 
 */

/**
 * Classe menu : classe de gestion des menus (liens entre un produit de catégories menu et ce qui peut le composer)
 */

class menu extends _model {

    /**
     * Attributs
     */

    // Nom de la table dans la BDD
    protected $table = "menu";
    // Abbréviation de la table dans la BDD
    protected $abrev_table = "m";
    
    // Tableau des produits possibles
    protected $produits = [];
    
    /**
     * Méthodes
     */

    /**
     * Définit les produits possibles pour une ligne du menu
     *
     * @param array $arrayProduits Tableau des produits
     * @return boolean True si tout est OK sinon false
     */
    public function set_produits($arrayProduits) {
        $this->produits = $arrayProduits;
    }

    /**
     * Retourne les produits possibles pour une ligne du menu
     *
     * @return array
     */
    public function get_produits() {
        return $this->produits;
    }

    /**
     * Retourne la composition d'un menu
     *
     * @param integer $idproduit Identifiant du produit menu
     * @return array Liste de la composition du menu
     */
    public function listComposition($idproduit){
        // On récupère la liste pour notre produit
        return $this->list(
            [],
            [
                [
                    "champ" => "m_ref_produit_menu",
                    "valeur" => $idproduit,
                    "operateur" => "=",
                    "table" => "menu"
                ]
            ]
        );
    }

    /**
     * Remet à zéro la composition d'un menu
     *
     * @param integer $idproduit Identifiant du produit menu
     * @return boolean True si tout s'est bien passé sinon false
     */
    public function resetComposition($idproduit){
        // On récupère la liste pour notre produit
        $arrayComposition = $this->listComposition($idproduit);

        // On parcourt les compositions du menu et on les supprime
        foreach ($arrayComposition as $composition) {
            $composition->delete();
        }

        return true;
    }

    /**
     * Retourne la composition d'un menu avec les possiblités pour chaque choix
     *
     * @param integer $idproduit Identifiant du produit menu
     * @return array Liste de la composition du menu avec pour chacun les choix possibles
     */
    public function listCompositionChoix($idproduit){
        // On récupère la liste pour notre produit
        $arrayComposition = $this->listComposition($idproduit);

        // Pour chaque composition, on va chercher les produits
        foreach ($arrayComposition as $key => $composition) {
            // On instancie un objet produit pour récuperer la liste
            $objProduit = new produit();
            // On récupère la liste que l'on stocke
            $arrayComposition[$key]->set_produits($objProduit->list(
                [],
                [
                    [
                        "champ" => "p_ref_categorie",
                        "valeur" => $composition->getValue("m_ref_categorie_possible"),
                        "operateur" => "=",
                        "table" => "produit"
                    ]
                ]
            ));
        }

        return $arrayComposition;
    }
}
