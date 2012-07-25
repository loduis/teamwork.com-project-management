<?php

require dirname(__DIR__) . '/autoload.php';
require __DIR__ . '/functions.php';

// START configurtion
const API_COMPANY = 'phpapi2';
const API_KEY = 'horse48street';
const API_FORMAT = 'xml';
// set your keys
TeamWorkPm::setAuth(API_COMPANY, API_KEY);
// set format not need, by default json format api
TeamWorkPm::setFormat(API_FORMAT);