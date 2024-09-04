<?php

/**
 * Classe de gestion des permissions
 */

class _permission {

    /**
     * Attributs
     */

    protected static $objPermission; //Objet unique sur la classe permission
    protected $urlRedirect = "index.php";

    //Constante pour définir les permissions
    protected const PERMISSIONS = [
        "ADMIN" => [
            "produit" => [
                "create" => ["autorised" => true, "partitionnement" => false],
                "read"  => ["autorised" => true, "partitionnement" => false],
                "update" => ["autorised" => true, "partitionnement" => false],
                "delete" => ["autorised" => true, "partitionnement" => false]
            ],
            "commande" => [
                "create" => ["autorised" => true, "partitionnement" => false],
                "read"  => ["autorised" => true, "partitionnement" => false],
                "update" => ["autorised" => true, "partitionnement" => false],
                "delete" => ["autorised" => true, "partitionnement" => false],
                "preparer" => ["autorised" => true, "partitionnement" => false],
                "livrer" => ["autorised" => true, "partitionnement" => false],
            ],
            "utilisateur" => [
                "create" => ["autorised" => true, "partitionnement" => false],
                "read"  => ["autorised" => true, "partitionnement" => false],
                "update" => ["autorised" => true, "partitionnement" => false],
                "delete" => ["autorised" => true, "partitionnement" => false],
            ],
            "piecejointe" => [
                "create" => ["autorised" => true, "partitionnement" => false],
                "read"  => ["autorised" => true, "partitionnement" => false],
                "update" => ["autorised" => true, "partitionnement" => false],
                "delete" => ["autorised" => true, "partitionnement" => false],
            ],
            "utilisateur" => [
                "create" => ["autorised" => true, "partitionnement" => false],
                "read"  => ["autorised" => true, "partitionnement" => false],
                "update" => ["autorised" => true, "partitionnement" => false],
                "delete" => ["autorised" => true, "partitionnement" => false],
            ]
        ],
        "ACCUEIL" => [
            "produit" => [
                "create" => ["autorised" => false, "partitionnement" => false],
                "read"  => ["autorised" => true, "partitionnement" => false],
                "update" => ["autorised" => false, "partitionnement" => false],
                "delete" => ["autorised" => false, "partitionnement" => false]
            ],
            "commande" => [
                "create" => ["autorised" => true, "partitionnement" => false],
                "read"  => ["autorised" => true, "partitionnement" => false],
                "update" => ["autorised" => true, "partitionnement" => false],
                "delete" => ["autorised" => false, "partitionnement" => false],
                "preparer" => ["autorised" => false, "partitionnement" => false],
                "livrer" => ["autorised" => true, "partitionnement" => false],
            ],
            "utilisateur" => [
                "create" => ["autorised" => false, "partitionnement" => false],
                "read"  => ["autorised" => true, "partitionnement" => true],
                "update" => ["autorised" => false, "partitionnement" => false],
                "delete" => ["autorised" => false, "partitionnement" => false],
            ],
            "piecejointe" => [
                "create" => ["autorised" => false, "partitionnement" => false],
                "read"  => ["autorised" => true, "partitionnement" => false],
                "update" => ["autorised" => false, "partitionnement" => false],
                "delete" => ["autorised" => false, "partitionnement" => false],
            ],
            "utilisateur" => [
                "create" => ["autorised" => false, "partitionnement" => false],
                "read"  => ["autorised" => false, "partitionnement" => false],
                "update" => ["autorised" => false, "partitionnement" => false],
                "delete" => ["autorised" => false, "partitionnement" => false],
            ]
        ],
        "PREPARATION" => [
            "produit" => [
                "create" => ["autorised" => false, "partitionnement" => false],
                "read"  => ["autorised" => true, "partitionnement" => false],
                "update" => ["autorised" => false, "partitionnement" => false],
                "delete" => ["autorised" => false, "partitionnement" => false]
            ],
            "commande" => [
                "create" => ["autorised" => false, "partitionnement" => false],
                "read"  => ["autorised" => true, "partitionnement" => false],
                "update" => ["autorised" => true, "partitionnement" => false],
                "delete" => ["autorised" => false, "partitionnement" => false],
                "preparer" => ["autorised" => true, "partitionnement" => false],
                "livrer" => ["autorised" => false, "partitionnement" => false],
            ],
            "utilisateur" => [
                "create" => ["autorised" => false, "partitionnement" => false],
                "read"  => ["autorised" => true, "partitionnement" => true],
                "update" => ["autorised" => false, "partitionnement" => false],
                "delete" => ["autorised" => false, "partitionnement" => false],
            ],
            "piecejointe" => [
                "create" => ["autorised" => false, "partitionnement" => false],
                "read"  => ["autorised" => true, "partitionnement" => false],
                "update" => ["autorised" => false, "partitionnement" => false],
                "delete" => ["autorised" => false, "partitionnement" => false],
            ],
            "utilisateur" => [
                "create" => ["autorised" => false, "partitionnement" => false],
                "read"  => ["autorised" => false, "partitionnement" => false],
                "update" => ["autorised" => false, "partitionnement" => false],
                "delete" => ["autorised" => false, "partitionnement" => false],
            ]
        ],
        "SUPERVISEUR" => [
            "produit" => [
                "create" => ["autorised" => false, "partitionnement" => false],
                "read"  => ["autorised" => true, "partitionnement" => false],
                "update" => ["autorised" => false, "partitionnement" => false],
                "delete" => ["autorised" => false, "partitionnement" => false]
            ],
            "commande" => [
                "create" => ["autorised" => true, "partitionnement" => false],
                "read"  => ["autorised" => true, "partitionnement" => false],
                "update" => ["autorised" => true, "partitionnement" => false],
                "delete" => ["autorised" => true, "partitionnement" => false],
                "preparer" => ["autorised" => true, "partitionnement" => false],
                "livrer" => ["autorised" => true, "partitionnement" => false],
            ],
            "utilisateur" => [
                "create" => ["autorised" => false, "partitionnement" => false],
                "read"  => ["autorised" => true, "partitionnement" => true],
                "update" => ["autorised" => false, "partitionnement" => false],
                "delete" => ["autorised" => false, "partitionnement" => false],
            ],
            "piecejointe" => [
                "create" => ["autorised" => false, "partitionnement" => false],
                "read"  => ["autorised" => true, "partitionnement" => false],
                "update" => ["autorised" => false, "partitionnement" => false],
                "delete" => ["autorised" => false, "partitionnement" => false],
            ],
            "utilisateur" => [
                "create" => ["autorised" => false, "partitionnement" => false],
                "read"  => ["autorised" => false, "partitionnement" => false],
                "update" => ["autorised" => false, "partitionnement" => false],
                "delete" => ["autorised" => false, "partitionnement" => false],
            ]
        ]
    ];

    /**
     * Méthodes
     */

    //Méthode pour travailler avec une instance unique sur cette classe
    public static function getPermission(){
        if(empty(static::$objPermission)){
            static::$objPermission = new _permission();
        }

        return static::$objPermission;
    }

    /**
     * Redirige l'utilisateur vers l'URL défini dans l'objet
     *
     * @param string $raison Raison de la redirection
     * @return void
     */
    public function redirect($raison = ""){
        header("Location: " . $this->urlRedirect . "?redirect=" . $raison);
        exit();
    }

    /**
     * Vérifie que l'utilisateur connecté peut exécuter une action
     *
     * @param  string $objet Objet concerné
     * @param  string $action Action à réaliser
     * @return boolean True si l'utilisateur est autorisé sinon False
     */
    public function verifPermission($objet,$action){
        //On récupère la session
        $objSession = _session::getSession();
        //On vérifie que la session existe bien
        if( ! $objSession->isConnected()) {
            //Si on a pas de session, on retourne false
            return false;
        }
        else {
            //On récupère l'objet de l'utilisateur connecté
            $objUser = $objSession->userConnected();
        }

        // On regarde si on a un champ rôle sur l'utilisateur
        $role = ($objUser->get("u_role_user")->is()) ? $objUser->getValue("u_role_user") : "ALL";

        return self::PERMISSIONS[$role][$objet][$action]["autorised"];
    }

    /**
     * Vérifie le partitionnement des données autorisé pour un objet, une action et pour l'utilisateur connecté
     *
     * @param  string $objet Objet concerné
     * @param  string $action Action à réaliser
     * @return boolean True si l'utilisateur voit uniquement ses informations sinon False il voit tout
     */
    public function getPartitionnement($objet,$action){
        //On récupère la session
        $objSession = _session::getSession();
        //On vérifie que la session existe bien
        if( ! $objSession->isConnected()) {
            //Si on a pas de session, on retourne false
            return false;
        }
        else {
            //On récupère l'objet de l'utilisateur connecté
            $objUser = $objSession->userConnected();
        }

        // On regarde si on a un champ rôle sur l'utilisateur
        $role = ($objUser->get("u_role_user")->is()) ? $objUser->getValue("u_role_user") : "ALL";

        return self::PERMISSIONS[$role][$objet][$action]["partitionnement"];
    }
}
