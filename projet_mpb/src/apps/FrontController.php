<?php

namespace Apps;

class FrontController {

    public static function handleRequest()
    {
        $controllerName = "Home";
        $task = "showHomePage";

        if(!empty($_GET['controller'])) {
            // si GET => user
            // alors User
            $controllerName = ucfirst($_GET['controller']);
        }

        if(!empty($_GET['task'])) {
            $task = $_GET['task'];
        }

        $controllerName = "Controllers\\" . $controllerName;

        $controller = new $controllerName();
        $controller->$task();
    }

    /*
    // Constantes
    private const ROUTES = [
        'home' => HomeController::class . '@home',
        'contact' => HomeController::class . '@contact',
        'register' => UserController::class . '@register',
        'login' => UserController::class . '@login',
        'member' => UserController::class . '@member',
        'logout' => UserController::class . '@logout',
        'cart' => UserController::class . '@cart',
        'product' => ProductController::class . '@product',
        'command' => UserController::class . '@command',
        'opinion' => UserController::class . '@opinion',
        'productSheet' => ProductController::class . '@productSheet',
        'admin' => UserController::class . '@admin'
    ];
    
    public static function handleRequest(): void
    {
        
        // Dispatch
        // Appel d'une constante appartenant à la classe 'self::CONSTANTE'
        if (array_key_exists($_GET['page'], self::ROUTES)) {
            // si la route existe, je charge le contrôleur et la méthode associée
            if (isset(self::ROUTES[$_GET['page']])) {
                // Obtention de la route à exécuter
                $route = self::ROUTES[$_GET['page']];
                // Extraction du contrôleur et de la méthode associée (via destructuring)
                [$controllerName, $methodName] = explode('@', $route);
                // Importe la classe à instancier, si on utilise pas Composer
                // require $controllerName . '.php';
                $controllerName = "Controllers\\" . $controllerName;
                // Instanciation
                $controller = new $controllerName();
                // Appel de la méthode
                $controller->$methodName();
            }
        }
    } */
}