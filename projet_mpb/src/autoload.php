<?php
// Autoloader
spl_autoload_register(function($className) {
    //Upper and lower case management
    $className = lcfirst(str_replace("\\", "/", $className));
    // example:
    // className = Controllers\Home -> controllers/Home
    // require_once = src/controllers/Home.php
    require_once("src/$className.php");
});