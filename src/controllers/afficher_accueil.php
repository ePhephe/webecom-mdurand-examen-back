<?php

/**
 * Classe afficher_accueil : classe du controller afficher_accueil
 * * Rôle : Prépare et affiche la page d'accueil
 */

class afficher_accueil extends _controller {

    /**
     * Attributs
     */

    // Nom du controller
    protected $name = "afficherAccueil";
    // Liste des objets manipulés par le controller
    protected $objects = ["commande" => ["read"]]; // ["objet1" => ["action"1,"action2"...],"objet2" => ["action"1,"action2"...]]

    // * Paramètres du controller attendus en entrée
    // * Néant
    protected $paramEntree = []; // ["nom_param1"=>["method"=>"POST","required"=>true],"nom_param2"=>["method"=>"POST","required"=>false]]

    // Type de retour
    protected $typeRetour = "pages"; // json, fragments ou pages (défaut)
    // Nom du template
    protected $template = "accueil";
    // Tableau de paramètre du template
    protected $paramTemplate = [ // ["head" => ["title" => "", "metadescription" => "", "lang" => ""], "is_nav" => true, "is_footer" => true]
        "head" => [
            "title" => "Accueil de votre interface Wakdo",
            "metadescription" => "Interface de gestion interne des produits et des commandes Wakdo",
            "lang" => "fr"
        ],
        "is_nav" => true,
        "is_footer" => true
    ];
    // Paramètres en sortie du controller
    protected $paramSortie = ["arrayListCommandes" => ["required" => true]]; // ["nom_param1"=>["required"=>true],"nom_param2"=>["required"=>false]]
    // Besoin d'être connecté
    protected $connected = true; // True par défaut

    /**
     * Exécution du rôle du controller
     *
     * @return boolean True si tout s'est bien passé, False si une erreur est survenu
     */
    public function execute()
    {
        // On instancie un objet de commande
        $commande = new commande();

        //Selon le rôle de l'utilisateur, on affiche des informations différentes
        // On récupère le rôle
        $role_user = $this->session->userConnected()->getValue("u_role_user");
        switch ($role_user) {
            case 'ADMIN':
                // Pour un admin, les 50 dernières commandes
                $arrayListCommandes = $commande->list(
                    [],
                    [],
                    ["c_id" => "DESC"],
                    ["limit" => 50, "offset" => 0]
                );
                break;
            case 'ACCUEIL':
                // Pour un équipier d'accueil, les commandes prête ou créée
                $arrayListCommandes = $commande->list(
                    [],
                    [
                        [
                            "champ" => "bloc",
                            "valeur" => [
                                [
                                    "champ" => "c_statut",
                                    "valeur" => "C",
                                    "operateur" => "=",
                                    "table" => "commande"
                                ],
                                [
                                    "champ" => "c_statut",
                                    "valeur" => "P",
                                    "operateur" => "=",
                                    "table" => "commande"
                                ]
                            ],
                            "operateur" => "OR"
                        ]
                    ],
                    ["c_statut" => "DESC", "c_datetime_livraison" => "ASC"]
                );
                break;
            case 'PREPARATION':
                // Pour un équipier de préparation, les commandes à préparer
                $arrayListCommandes = $commande->list(
                    [],
                    [
                        [
                            "champ" => "c_statut",
                            "valeur" => "AP",
                            "operateur" => "=",
                            "table" => "commande"
                        ]
                    ],
                    ["c_datetime_livraison" => "ASC"]
                );
                break;
            case 'SUPERVISEUR':
                // Pour un superviseur, les commandes à préparer triées par livraison croissante
                $arrayListCommandes = $commande->list(
                    [],
                    [
                        [
                            "champ" => "c_statut",
                            "valeur" => "S",
                            "operateur" => "<>",
                            "table" => "commande"
                        ]
                    ],
                    ["c_datetime_livraison" => "ASC"]
                );
                break;
            default:
                // Par défaut un tableau vide
                $arrayListCommandes = [];
                break;
        }

        // On récupère la liste des dix derniers projets trier par id les plus récents et avec un statut Accepté ou Financé
        $this->sortie["arrayListCommandes"] = $arrayListCommandes;
    }
}
