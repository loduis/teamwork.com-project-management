<?php

ini_set('display_errors', TRUE);

// forzamos a usar excepciones
set_error_handler(function($errno, $errstr, $errfile, $errline)
{
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

function __autoload($class)
{
    $file = dirname(__FILE__);
    if (strpos($class, '_') !== FALSE) {
        $file = dirname($file);
        $class = str_replace('_', DIRECTORY_SEPARATOR, $class);
    }

    $file .= DIRECTORY_SEPARATOR . $class . '.php';

    require $file;
}