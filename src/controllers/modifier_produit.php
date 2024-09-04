<?php

/**
 * Classe modifier_produit : classe du controller modifier_produit
 * * Rôle : Modifie un produit dans la base de données
 */

class modifier_produit extends _controller {

    /**
     * Attributs
     */

    // Nom du controller
    protected $name = "ModifierProduit";
    // Liste des objets manipulés par le controller
    protected $objects = ["produit" => ["update"]]; // ["objet1" => ["action"1,"action2"...],"objet2" => ["action"1,"action2"...]]

    // * Paramètres du controller attendus en entrée
    // * Informations du projet - Issu du formulaire correspondant - POST - Requis
    // * idproduit - Identifiant du produit à modifier
    protected $paramEntree = [ // ["nom_param1"=>["method"=>"POST","required"=>true],"nom_param2"=>["method"=>"POST","required"=>false]]
        "form" => ["method" => "_POST", "required" => true],
        "id" => ["method" => "_GET", "required" => true]
    ]; 
    
    // Type de retour
    protected $typeRetour = "json"; // json, fragments ou pages (défaut)
    // Nom du template
    protected $template = "";
    // Tableau de paramètre du template
    protected $paramTemplate = [ // ["head" => ["title" => "", "metadescription" => "", "lang" => ""], "is_nav" => true, "is_footer" => true]
    ];
    // Paramètres en sortie du controller
    protected $paramSortie = ["action"=>["required"=>false]]; // ["nom_param1"=>["required"=>true],"nom_param2"=>["required"=>false]]
    // Besoin d'être connecté
    protected $connected = false; // True par défaut

    /**
     * Vérifie que les paramètres du controller sont bien présents et/ou leur cohérence
     *
     * @return boolean True si tout s'est bien passé, False si une erreur est survenu
     */
    public function verifParams(){
        // On teste le verifParams parent, si c'est OK on continue
        if(parent::verifParams()) {
            // Traitement parituclier pour le isdispo qui est une checkbox
            if(isset($this->parametres["form"]["p_isdispo"])){
                $this->parametres["form"]["p_isdispo"] = 1;
            }
            else {
                $this->parametres["form"]["p_isdispo"] = "0";
            }

            // On convertir les virgules en points sur le prix
            if(isset($this->parametres["form"]["p_prix"])){
                $this->parametres["form"]["p_prix"] = str_replace(',', '.', $this->parametres["form"]["p_prix"]);
            }

            // On vérifie que l'on a au moins les champs essentiels
            return !empty($this->parametres["form"]["p_libelle"]) &&
            !empty($this->parametres["form"]["p_prix"]) &&
            !empty($this->parametres["form"]["p_description"]) &&
            !empty($this->parametres["form"]["p_stock"]) &&
            !empty($this->parametres["form"]["p_ref_categorie"]);
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
        $objProduit = new produit($this->parametres["id"]);
        // On commence par appeler la vérification des paramètres
        if($this->verifParams()) {
            // On vérifie que les paramètres sont OK
            if($objProduit->verifParamsFormulaire($this->parametres["form"])){
                // On réalise la mise à jour dans la base de données
                $resultat = $objProduit->update();

                // On regarde si on est sur une catégorie menu ou non
                if($objProduit->getValue("p_ref_categorie") == 1) {
                    // Dans ce cas, on traite le menu
                    // On rénitiliase la composition du menu
                    $objMenu = new menu();
                    $objMenu->resetComposition($objProduit->id());

                    // Il faut parcourir les inputs des choix
                    foreach ($this->parametres["form"]["m_ref_categorie_possible"] as $key => $value) {
                        if(!empty($this->parametres["form"]["m_ref_categorie_possible"][$key]) && !empty($this->parametres["form"]["m_libelle"][$key])) {
                            // On instancie un objet de menu
                            $objMenu = new menu();

                            // On lui met les valeurs nécessaire
                            $objMenu->set("m_libelle",$this->parametres["form"]["m_libelle"][$key]);
                            $objMenu->set("m_ref_produit_menu",$objProduit->id());
                            $objMenu->set("m_ref_categorie_possible",$value);

                            // On enregistre
                            $objMenu->insert();
                        }
                    }
                }
                
                // Si la modification a fonctionné
                if($resultat === true) {
                    // On prépare le retour du succès
                    $this->sortie["action"] = "update";
                    $this->makeRetour(true,"succes","Votre produit a été mis à jour avec succès !");
                }
                else {
                    //On met en forme une erreur si les paramètres ne sont pas présents
                    $this->makeRetour(false,"echec","Votre produit n'a pas pu être mis à jour.");
                }
            }
            else {
                //On met en forme une erreur si les paramètres ne sont pas présents
                $this->makeRetour(false,"echec","(1) Les paramètres fournis ne sont pas corrects.");
            }
        }
        else {
            //On met en forme une erreur si les paramètres ne sont pas présents
            $this->makeRetour(false,"echec","(2) Les paramètres fournis ne sont pas corrects.");
        }
    }
}
