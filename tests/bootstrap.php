<?php

require __DIR__ . '/../vendor/autoload.php';

// Start auth here to do so only once

\TeamWorkPm\Auth::set(getenv('API_COMPANY'), getenv('API_KEY'));
\TeamWorkPm\Rest::setFormat(getenv('API_FORMAT'));
