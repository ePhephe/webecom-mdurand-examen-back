<?php

/**
 * 
 * Attributs disponibles
 * 
 * Méthodes disponibles
 * @method get_element_form_cp_ref_format() Surcharge l'élément de formulaire cp_ref_format
 * @method addProduit() Ajoute un produit dans une commande
 * 
 */

/**
 * Classe commande_produit : classe de gestion des produits dans les commandes
 */

class commande_produit extends _model
{

    /**
     * Attributs
     */

    // Nom de la table dans la BDD
    protected $table = "commande_produit";
    // Abbréviation de la table dans la BDD
    protected $abrev_table = "cp";

    /**
     * Méthodes
     */

    /**
     * Construit la liste déroulante de format pour les formulaires
     *
     * @param array $infosChamp Informations sur le champ
     * @param string $acces Accès particulier aux champs (ex. readonly)
     * @param array $others Autres paramètres possibles
     * @return string Template HTML du champ
     */
    public function get_element_form_cp_ref_format($infosChamp = [], $acces = "", $others = [])
    {
        // On récupère l'objet champ
        $objField = $this->get("cp_ref_format");

        // On prépare le nom de l'input en fonction de la présence d'éléments dans infoChamps
        if (isset($infosChamp["prefix_name"])) {
            $name = $infosChamp["prefix_name"] . "_" . $objField->get("input")["name"];
        } else {
            $name = $objField->get("input")["name"];
        }

        // On met en place le label correspondant au champ
        $divInputGlobal = '<div id="div_' . $name . '" class="div_input_form">';
        $labelPrincipal = '<label for="' . $name . '">' . $objField->get("libelle") . ' : </label>';

        $inputPrincipal = '<select name="' . $name . '" id="' . $objField->get("input")["id"] . '">';

        // Si on a un niveau d'accès au champ défini, on le spécifie sur le select (un peu différent)
        $accesSelect = ((!empty($acces)) ? 'disabled' : "");

        // Gestion si la liste fait référence à un autre objet
        if (!empty($objField->get("nomObjet"))) {
            //On récupère le nom de l'objet
            $nomObjet = $objField->get("nomObjet");
            // On instancie notre objet
            $obj = new $nomObjet();
            // On parcourt les éléments pour créé la liste clé-valeur attendu
            $filtres = (isset($others[$name]["filtres"])) ? $others[$name]["filtres"] : [];
            $listeElement = $obj->list([],$filtres);
            foreach ($listeElement as $cle => $valeur) {
                $listOptions[$cle] = $valeur;
            }
        } else {
            $listOptions = $objField->get("listCleValeur");
        }

        // On définit les options possibles du select
        foreach ($listOptions as $cle => $valeur) {
            // On initialise quelques variables
            // On indique le paramètre selected de l'option si elle correspond à la valeur du champ
            $selected = ($objField->get("value") === strval($cle)) ? "selected" : "";
            // On indique le paramètre d'accès de l'option, selon le selected et le accesSelect
            $accesOption = ((!empty($accesSelect) && empty($selected)) ? "disabled" : "");

            // Si jamais on a un paramètre autorised_value dans le infosChamp
            if (isset($infosChamp["autorised_value"])) {
                // Si la clé est présente dans les valeurs autorisées
                if (in_array($cle, $infosChamp["autorised_value"]))
                    $inputPrincipal .= ' <option data-prix="' . $valeur->getValue("f_format_prix") . '" value="' . $cle . '" ' . $selected . ' ' . $accesOption . '>' . $valeur->getValue("f_libelle") . '</option>';
            } else {
                $inputPrincipal .= ' <option data-prix="' . $valeur->getValue("f_format_prix") . '" value="' . $cle . '" ' . $selected . ' ' . $accesOption . '>' . $valeur->getValue("f_libelle") . '</option>';
            }
        }
        $inputPrincipal .= '</select>';

        // On retourne le template
        return $divInputGlobal . $labelPrincipal . $inputPrincipal . '</div>';
    }

    /**
     * Ajoute un produit dans une commande
     *
     * @param integer $idcommande Identifiant de la commande concernée
     * @param array $arrayInfosCdeProduits Tableau des informations sur le produit
     * @return boolean True si la création s'est bien passée, sinon false
     */
    public function addProduit($idcommande,$arrayInfosCdeProduits) {
        // On charge un objet produit correspondant à ce dernier
        $objProduit = new produit($arrayInfosCdeProduits["id"]);

        // On charge les informations dans la commande_produit
        // Le commande
        $this->set("cp_ref_commande",$idcommande);
        // Le produit
        $this->set("cp_ref_produit",$objProduit->id());
        // La quantité, et on la supprime côté produit
        $this->set("cp_quantite",$arrayInfosCdeProduits["quantite"]);
        $objProduit->moinsStock($arrayInfosCdeProduits["quantite"]);
        // Le format choisit pour le produit
        $this->set("cp_ref_format",$arrayInfosCdeProduits["format"]);

        $this->insert();

        // On regarde si on est dans un menu
        if(key_exists("produitsMenu",$arrayInfosCdeProduits)) {
            // Dans ce cas, on ajoute les produits du menu
            foreach ($arrayInfosCdeProduits["produitsMenu"] as $produitMenu) {
                // On charge un objet produit correspondant à ce dernier
                $objProduitMenu = new produit($produitMenu["id"]);
                // On charge les informations dans la commande_produit
                // On instancie un objet
                $objCdeProduit = new commande_produit();
                // Le commande
                $objCdeProduit->set("cp_ref_commande",$idcommande);
                // Le produit
                $objCdeProduit->set("cp_ref_produit",$objProduitMenu->id());
                // La quantité est obligatoirement à 1
                $objCdeProduit->set("cp_quantite",1);
                $objProduit->moinsStock(1);
                // La référence au menu
                $objCdeProduit->set("cp_ref_produit_menu",$objProduit->id());
                $objCdeProduit->set("cp_ref_menu_commande",$this->id());

                // On insert dans la BDD
                $objCdeProduit->insert();
            }
        }
    }
}
