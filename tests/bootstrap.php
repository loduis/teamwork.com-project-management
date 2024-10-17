<?php

require __DIR__ . '/../vendor/autoload.php';

\TeamWorkPm\Auth::set(getenv('API_COMPANY'), getenv('API_KEY'));
\TeamWorkPm\Rest::setFormat(getenv('API_FORMAT'));
