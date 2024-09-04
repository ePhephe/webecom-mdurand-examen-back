<?php

/**
 * Classe lister_utilisateurs : classe du controller lister_utilisateurs
 * * Rôle : Prépare et affiche la liste des utilisateurs
 */

class lister_utilisateurs extends _controller {

    /**
     * Attributs
     */

    // Nom du controller
    protected $name = "ListerUtilisateurs";
    // Liste des objets manipulés par le controller
    protected $objects = ["utilisateur"=>["read"]]; // ["objet1" => ["action"1,"action2"...],"objet2" => ["action"1,"action2"...]]

    // * Paramètres du controller attendus en entrée
    // * Filtres possibles - Filtres et tris à appliquer à la liste - REQUEST - Facultatif
    protected $paramEntree = [ // ["nom_param1"=>["method"=>"POST","required"=>true],"nom_param2"=>["method"=>"POST","required"=>false]]
        "recherche_user" => ["method" => "_REQUEST", "required" => false],
        "role_user" => ["method" => "_REQUEST", "required" => false],
        "statut" => ["method" => "_REQUEST", "required" => false]
    ];
    
    // Type de retour
    protected $typeRetour = "pages"; // json, fragments ou pages (défaut)
    // Nom du template
    protected $template = "list_utilisateurs";
    // Tableau de paramètre du template
    protected $paramTemplate = [ // ["head" => ["title" => "", "metadescription" => "", "lang" => ""], "is_nav" => true, "is_footer" => true]
        "head" => [
            "title" => "Liste des utilisateurs de l'application interne - Wakdo",
            "metadescription" => "Gérez les utilisateurs. Créez, modifiez ou supprimez des utilisateurs.",
            "lang" => "fr"
        ],
        "is_nav" => true,
        "is_footer" => true
    ];
    // Paramètres en sortie du controller
    protected $paramSortie = [ // ["nom_param1"=>["required"=>true],"nom_param2"=>["required"=>false]]
        "arrayListUsers"=>["required"=>true],
        "recherche_user"=>["required"=>false],
        "statut"=>["required"=>false],
        "role_user"=>["required"=>false]
    ];
    // Besoin d'être connecté
    protected $connected = true; // True par défaut

    /**
     * Vérifie que les paramètres du controller sont bien présents et/ou leur cohérence
     *
     * @return boolean True si tout s'est bien passé, False si une erreur est survenu
     */
    public function verifParams(){
        // On appelle la méthod parente
        parent::verifParams();

        // On initialise les potentiels filtres
        $this->parametres["filtres"] = [];

        // Critère de recherche texte
        if (!empty($this->parametres["recherche_user"])) {
            $this->parametres["filtres"][] = [
                "champ" => "bloc",
                "valeur" => [
                    [
                        "champ" => "u_nom",
                        "valeur" => $this->parametres["recherche_user"],
                        "operateur" => "LIKE",
                        "table" => $this->session->getTableUser()
                    ],
                    [
                        "champ" => "u_prenom",
                        "valeur" => $this->parametres["recherche_user"],
                        "operateur" => "LIKE",
                        "table" => $this->session->getTableUser()
                    ],
                    [
                        "champ" => "u_email",
                        "valeur" => $this->parametres["recherche_user"],
                        "operateur" => "LIKE",
                        "table" => $this->session->getTableUser()
                    ]
                ],
                "operateur" => "OR"
            ];
        }
        // Critère de recherche role de l'utilisateur
        if (!empty($this->parametres["role_user"])) {
            if ($this->parametres["role_user"] != "ALL") {
                $this->parametres["filtres"][] = [
                    "champ" => "u_role_user",
                    "valeur" => $this->parametres["role_user"],
                    "operateur" => "=",
                    "table" => $this->session->getTableUser()
                ];
            }
        }
        // Critère de recherche statut de l'utilisateur
        if (!empty($this->parametres["statut"])) {
            if ($this->parametres["statut"] != "ALL") {
                $this->parametres["filtres"][] = [
                    "champ" => "u_statut",
                    "valeur" => $this->parametres["statut"],
                    "operateur" => "=",
                    "table" => $this->session->getTableUser()
                ];
            }
        }


        return true;
    }

    /**
     * Exécution du rôle du controller
     *
     * @return boolean True si tout s'est bien passé, False si une erreur est survenu
     */
    public function execute(){
        // On appelle la fonction de vérification des paramètres parente
        $this->verifParams();

        // On instancie un objet utilisateur
        $nomObjet = $this->session->getTableUser();
        $objUtilisateur = new $nomObjet();

        // On retourne la liste des utilisateur
        $this->sortie["arrayListUsers"] = $objUtilisateur->list(
            [],
            $this->parametres["filtres"],
            ["u_id" => "DESC"]
        );
        // On récupère nos paramètres de filtre
        $this->sortie["recherche_user"] = (isset($this->parametres["recherche_user"])) ? $this->parametres["recherche_user"] : "";
        $this->sortie["statut"] = (isset($this->parametres["statut"])) ? $this->parametres["statut"] : "ALL";
        $this->sortie["role_user"] = (isset($this->parametres["role_user"])) ? $this->parametres["role_user"] : "ALL";
    }
}
