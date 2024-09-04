<?php

/**
 *
 * Attributs disponibles
 *
 * Méthodes disponibles
 * @method listFormatCategorie() Retourne la liste des formats possibles pour une catégorie
 *
 */

/**
 * Classe format : classe de gestion des formats
 */

class format extends _model {

    /**
     * Attributs
     */

    // Nom de la table dans la BDD
    protected $table = "format";
    // Abbréviation de la table dans la BDD
    protected $abrev_table = "f";
    
    /**
     * Méthodes
     */

    /**
     * Retourne la liste des formats possibles pour une catégorie
     *
     * @param integer $idcategorie Identifiant de la catégorie
     * @return array Tableau d'objet des formats possibles
     */
    public function listFormatCategorie($idcategorie) {
        return $this->list(
            [],
            [
                [
                    "champ" => "f_ref_categorie",
                    "valeur" => $idcategorie,
                    "operateur" => "=",
                    "table" => "format"
                ]
            ]
        );
    }
    
}
