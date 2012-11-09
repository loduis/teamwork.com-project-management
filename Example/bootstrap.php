<?php

ini_set('display_errors', true);

// forzamos a usar excepciones
set_error_handler(function($errno, $errstr, $errfile, $errline)
{
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

require dirname(__DIR__) . '/TeamWorkPm.php';

require __DIR__ . '/functions.php';

// START configurtion
const API_COMPANY = 'phpapi2';
const API_KEY = 'horse48street';
const API_FORMAT = 'json';
// set your keys
TeamWorkPm::setAuth(API_COMPANY, API_KEY);
// set format not need, by default json format api
TeamWorkPm::setFormat(API_FORMAT);