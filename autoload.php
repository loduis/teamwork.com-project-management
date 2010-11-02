<?php

function __autoload($class)
{
    $file = dirname(__FILE__);
    if (strpos($class, '_') !== false) {
        $file = dirname($file);
        $class = str_replace('_', DIRECTORY_SEPARATOR, $class);
    }
    
    $file .= DIRECTORY_SEPARATOR . $class . '.php';

    require_once $file;
}