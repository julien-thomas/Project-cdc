<?php

spl_autoload_register(function($className) {
    // className = Controllers\Article
    // require = src/Controllers/Article.php
    $className = lcfirst(str_replace("\\", "/", $className));

    require_once("src/$className.php");
});