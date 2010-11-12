<?php

require_once '../autoload.php';

$Milestone = TeamWorkPm::factory('Milestone');
if ($Milestone->delete(26273)) {
    echo "save";
} else {
    echo $Milestone->getErrors();
}
/*
 THIS IS UN INSERT NEW MILESTON
$Milestone->save(array(
    'project_id'=> 16538,
    'title'=>'Live Php App',
    'deadline'=>20101125,
    'responsible_party_id'=>19496
));*/
// THIS IS UN UPDATED MILESTONE
/*
$Milestone->save(array(
    'id'=> 25669,
    'title'=>'Live Php App',
    'deadline'=>20101125,
    'isprivate'=> true,
    'responsible_party_ids'=>19496
));*/
//THIS IS UN MARK AS COMPLETE
//$Milestone->markAsComplete(25669);
//$Milestone->getAll('completed');