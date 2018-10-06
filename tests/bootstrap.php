<?php

ini_set('error_reporting', -1);

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
});

require __DIR__ . '/../vendor/autoload.php';

(new Dotenv\Dotenv(__DIR__))->load();
