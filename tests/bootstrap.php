<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__  . '/functions.php';
require __DIR__  . '/TestCase.php';

// Start auth here to do so only once

TeamWorkPm\Auth::set(API_COMPANY, API_KEY);
TeamWorkPm\Rest::setFormat(API_FORMAT);

