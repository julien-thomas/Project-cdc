<?php

// Init Session
if (session_status() === PHP_SESSION_NONE) session_start();

// Calling Autoloader
require_once('src/autoload.php');

// Calling Router
\Apps\FrontController::handleRequest();
