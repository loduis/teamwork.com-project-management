<?php
require_once '../autoload.php';

$account = TeamWorkPm::factory('Account');
$response = $account->getAuthenticate();
print_r($response);