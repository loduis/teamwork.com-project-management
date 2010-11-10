<?php

require_once '../autoload.php';

$Company = TeamWorkPm::factory('Company');
$response = $Company->getAll();
print_r($response);
