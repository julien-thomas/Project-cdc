<?php

namespace Models;

class Database {

    private static $bdd = null;

    /**
     * Retourne une connexion à la base de données
     * On précise ici deux options :
     * - Le mode d'erreur : le mode exception permet à PDO de nous prévenir s'il y a une erreur
     * - Le mode d'exploitation : FETCH_ASSOC veut dire qu'on exploitera les données sous la forme de tableaux associatifs
     * 
     * 
     * @return PDO
     */
    public static function getPdo()
    {
        if(self::$bdd === null) {
            self::$bdd = new \PDO('mysql:host=db.3wa.io;dbname=julienthomas_monpetitbouchon;charset=utf8', 'julienthomas', '9a9138af8571f20eaeab1bf5bf8b741a', [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
        ]);
        }
        

        return self::$bdd;
    }

    /* protected $bdd;

    public function __construct() {
        
            $this->bdd = new \PDO('mysql:host=db.3wa.io;dbname=julienthomas_monpetitbouchon;charset=utf8', 'julienthomas', '9a9138af8571f20eaeab1bf5bf8b741a', [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
        ]);
    } */

}