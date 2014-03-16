<?php

require __DIR__ . '/../vendor/autoload.php';

use \TeamWorkPm\Factory as TeamWorkPm;
use \TeamWorkPm\Auth;

Auth::set('horse48street');

$account = TeamWorkPm::build('account');

$data    = $account->get();

// Print data from api
echo $data, PHP_EOL;

// Save data to disk
$data->save('./account.json');