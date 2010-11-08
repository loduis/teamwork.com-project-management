<?php

require_once '../autoload.php';

$Project = TeamWorkPm::factory('Project');
$response = $Project->get(16532);
print_r($response);
