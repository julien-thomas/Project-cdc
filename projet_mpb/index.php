<?php

// Init Session
if (session_status() === PHP_SESSION_NONE) session_start();
require_once('src/autoload.php');
// Autoloader
/*
spl_autoload_register(function($class) {                            // $class = new Controllers\HomeController
    require_once lcfirst(str_replace('\\','/', $class)) . '.php';   // require_once controllers/HomeController.php
});
/*
// Test if user asked for a page
if (array_key_exists('page', $_GET)) {
    $router = new Controllers\FrontController();
    $router->handleRequest();
}
*/
\Apps\FrontController::handleRequest();
