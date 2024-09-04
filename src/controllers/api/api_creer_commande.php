<?php

/**
 * Classe api_creer_commande : classe du controller api_creer_commande
 * * Rôle : API de création d'un commande
 */

class api_creer_commande extends _controller {

    /**
     * Attributs
     */

    // Nom du controller
    protected $name = "apiCreerCommande";
    // Liste des objets manipulés par le controller
    protected $objects = ["commande" => ["create"]]; // ["objet1" => ["action"1,"action2"...],"objet2" => ["action"1,"action2"...]]

    // * Paramètres du controller attendus en entrée
    // * num_cde - Numéro de la commande - json - Requis
    // * ref_cde - Référence de la commande - json - Requis
    // * type_service - Service pour la commande (SP Sur place ou AE A emporter) - json - Requis
    // * num_chevalet - Numéro du chevalet dans le cas d'une commande sur place - json - Facultatif
    // * commentaire - Commentaire éventuel - json - Facultatif
    // * produits - Liste des produits contenus dans la commande - json - Requis
    protected $paramEntree = [ // ["nom_param1"=>["method"=>"POST","required"=>true],"nom_param2"=>["method"=>"POST","required"=>false]]
        "num_cde" => ["method" => "json", "required" => true],
        "ref_cde" => ["method" => "json", "required" => true],
        "type_service" => ["method" => "json", "required" => true],
        "num_chevalet" => ["method" => "json", "required" => false],
        "commentaire" => ["method" => "json", "required" => false],
        "produits" => ["method" => "json", "required" => true]
    ];
    
    // Type de retour
    protected $typeRetour = "json"; // json, fragment ou pages (défaut)
    // Nom du template
    protected $template = "";
    // Tableau de paramètre du template
    protected $paramTemplate = [ // ["head" => ["title" => "", "metadescription" => "", "lang" => ""], "is_nav" => true, "is_footer" => true]
    ];
    // Paramètres en sortie du controller
    protected $paramSortie = []; // ["nom_param1"=>["required"=>true],"nom_param2"=>["required"=>false]]
    // Besoin d'être connecté
    protected $connected = true; // True par défaut

    /**
     * Vérifie que les paramètres du controller sont bien présents et/ou leur cohérence
     *
     * @return boolean True si tout s'est bien passé, False si une erreur est survenu
     */
    public function verifParams(){
        // On vérifie déjà si les parmaètres sont OK avec la fonction parente
        if(!parent::verifParams()) {
            $this->makeRetour(false,"Paramètres KO","Les paramètres ne sont pas corrects.");
            return false;
        }

        // On test que la méthode est bien en POST
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            $this->makeRetour(false,"Method KO","Les paramètres ne sont pas corrects.");
            return false;
        }

        // On vérifie que le type de service est soit AE soit SP
        if(!($this->parametres["type_service"] === "AE" || $this->parametres["type_service"] === "SP")) {
            $this->makeRetour(false,"Service KO","Les paramètres ne sont pas corrects.");
            return false;
        }

        // On vérifie que si le service est SP on a un numéro de chevalet
        if($this->parametres["type_service"] === "SP" && !isset($this->parametres["num_chevalet"])) {
            $this->makeRetour(false,"Chevalet SP KO","Les paramètres ne sont pas corrects.");
            return false;
        }
        // Inversement si on est en AE, il n'en faut pas
        elseif(isset($this->parametres["num_chevalet"]) && $this->parametres["type_service"] === "AE") {
            $this->makeRetour(false,"Chevalet AE KO","Les paramètres ne sont pas corrects.");
            return false;
        }
        
        // On vérifie que le numéro de chevalet est bon
        if(isset($this->parametres["num_chevalet"]) && !(intval($this->parametres["num_chevalet"]) < 1000 && intval($this->parametres["num_chevalet"]) > 0)) {
            $this->makeRetour(false,"Chevalet KO","Les paramètres ne sont pas corrects.");
            return false;
        }

        // On vérifie que l'on a au moins 1 produit
        if(count($this->parametres["produits"]) < 1 ){
            $this->makeRetour(false,"Produits KO","Les paramètres ne sont pas corrects.");
            return false;
        }

        return true;
    }

    /**
     * Exécution du rôle du controller
     *
     * @return boolean True si tout s'est bien passé, False si une erreur est survenu
     */
    public function execute(){
        // On commence par appeler la vérification des paramètres
        if($this->verifParams()) {
            // On instancie un nouvelle objet de commande
            $objCommande = new commande();

            // On met les valeurs reçues
            $objCommande->set("c_num_cde",$this->parametres["num_cde"]);
            $objCommande->set("c_ref_cde",$this->parametres["ref_cde"]);
            $objCommande->set("c_type_service",$this->parametres["type_service"]);
            if(isset($this->parametres["num_chevalet"])){
                $objCommande->set("c_num_chevalet",$this->parametres["num_chevalet"]);
            }
            if(isset($this->parametres["commentaire"])) {
                $objCommande->set("c_commentaire",$this->parametres["commentaire"]);
            }
            // On indique le statut Créée
            $objCommande->set("c_statut","AP");
            // On indique l'heure de création et modification
            $objDate = new DateTime();
            $objCommande->set("c_datetime_creation",$objDate->format("Y-m-d H:i:s"));
            $objCommande->set("c_datetime_modification",$objDate->format("Y-m-d H:i:s"));
            // On indique l'heure de livraison prévue
            $dateLivraison = new DateTime();
            $dateLivraison->add(new DateInterval('PT5M'));
            $objCommande->set("c_datetime_livraison",$dateLivraison->format("Y-m-d H:i:s"));

            // On insert la commande
            if($objCommande->insert()) {
                // On va ajouter les produits à la commande
                // On parcourt les produits et on les ajoute
                foreach ($this->parametres["produits"] as $produit) {
                    // Chaque produit doit avoir un champ : quantite, id, format
                    // Dans le cas d'un menu on doit aussi avoir un tableau produitsMenu avec les id des produits
                    // On instancie un objet du commande_produit
                    $objCdeProduit = new commande_produit();
                    $objCdeProduit->addProduit($objCommande->id(),$produit);
                }
                //On met en forme le message de succès
                $this->makeRetour(true,"","La commande a été créée avec succès !");
            }
            else {
                //On met en forme une erreur si les paramètres ne sont pas présents
                $this->makeRetour(false,"error","Nous n'avons pas pu créer la commande");
            }

        }
        else {
            return false;
        }

        return true;
    }

}