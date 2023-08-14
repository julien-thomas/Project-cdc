<?php

namespace Models;

class Database {

    private static $bdd = null;

    /**
     * Returns a database connection
     * Two options are specified here :
     * - Error mode : exception mode allows PDO to warn us if there is an error
     * - Operating mode : FETCH_ASSOC means that we will exploit the data in the form of associative arrays
     * 
     * 
     * @return PDO
     */
    public static function getPdo() //use of static function to avoid instantiating an object
    {
        //use of singleton pattern
        //in order to restrict the number of instances that can be created from a resource consuming class to only one
        if(self::$bdd === null) {
            self::$bdd = new \PDO('mysql:host=db.3wa.io;dbname=julienthomas_monpetitbouchon;charset=utf8', 'julienthomas', '9a9138af8571f20eaeab1bf5bf8b741a', [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
        ]);
        }
        

        return self::$bdd;
    }
}