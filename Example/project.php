<?php

require_once '../autoload.php';

$project = TeamWorkPm::factory(TeamWorkPm::PROJECT);
$response = $project->getAll();
print_r($response);
unset ($response);