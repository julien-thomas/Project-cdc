<?php

class Renderer 
{
    // render('path')
    /**
     * Affiche un template HTML en injectant les $variables
     * 
     * @param string $path
     * @param array $variables
     * @return void
     */

    public static function render(string $path, string $path2, $arguments = []) 
    {
        extract($arguments);
        // View, if exist
        ob_start();
        require ('views/' .$path . '.phtml');
        $template =  ob_get_clean();
        // var_dump($template);
        // Template
        include 'views/' . $path2 . '.phtml';
        }
}