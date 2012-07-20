<?php

require_once '../autoload.php';

$project = TeamWorkPm::factory('Project');
//$response = $Project->get(16532);
$response = $project->getAll();
foreach ($response->projects as $_project) {
    //print_r($_project);
    echo $_project->id, "\n";
    echo $_project->name, "\n";
    echo '....', "\n";

}
echo "------------------------------\n";
// en formato arreglo
$responseAsArray = $response->toArray();
foreach ($responseAsArray['projects'] as $_project) {
    echo $_project['id'], "\n";
    echo $_project['name'], "\n";
}
