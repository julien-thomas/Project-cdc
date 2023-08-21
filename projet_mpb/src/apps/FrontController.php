<?php
// Router
namespace Apps;

class FrontController {

    /**
     * Calls the method from the controller
     *
     * @return void
     */
    public static function handleRequest()
    {
        // Default: controller=Home and method=showHomePage
        $controllerName = "Home";
        $task = "showHomePage";

        if(!empty($_GET['controller'])) {
            // Upper and lower case management
            $controllerName = ucfirst($_GET['controller']);
        }

        if(!empty($_GET['task'])) {
            $task = $_GET['task'];
        }

        $controllerName = "Controllers\\" . $controllerName;

        $controller = new $controllerName();
        $controller->$task();
    }

}