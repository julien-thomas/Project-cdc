<?php

namespace Apps;

class Renderer 
{
    /**
     * Displays an HTML template by injecting the $variables
     * 
     * @param string $path (view)
     * @param string $path2 (layout or admin)
     * @param array $arguments
     * @return void
     */

    public static function render(string $path, string $path2, $arguments = []) 
    {
        extract($arguments);
        // View
        ob_start(); //buffer opening
        require ('views/' .$path . '.phtml');
        $template =  ob_get_clean(); //get current buffer contents
        // Template
        include 'views/' . $path2 . '.phtml';
        }
}