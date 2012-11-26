<?php

require __DIR__  . '/functions.php';

// forzamos a usar excepciones
set_error_handler(function($errno, $errstr, $errfile, $errline)
{
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

spl_autoload_register(function ($className) {
    static $directories = array();
    if (!$directories) {
        $directories[] = __DIR__;
        $directories[] = dirname(__DIR__);
    }

    foreach($directories as $basedir) {
        $filename = $basedir . DIRECTORY_SEPARATOR . $className . '.php';
        if (file_exists($filename)) {
            require $filename;
            break;
        }
    }
});