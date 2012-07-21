<?php

ini_set('display_errors', true);

// forzamos a usar excepciones
set_error_handler(function($errno, $errstr, $errfile, $errline)
{
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

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

/**
 * Indents a flat JSON string to make it more human-readable
 *
 * @param string $json The original JSON string to process
 * @return string Indented version of the original JSON string
 */
function json_format($json) {


    return $result;
}