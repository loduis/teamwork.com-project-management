<?php

require './bootstrap.php';

// prepare test
test_bootstrap(function ($command) {
    if ( $command === 'get_by_project') {
        return get_first_project();
    }
});

function test_get() {
    $activity = TeamWorkPm::factory('Activity');
    try {
        $reports = $activity->get();
        foreach ($reports as $a) {
            print_r($a);
        }
    } catch (Exception $e) {
        print_r($e);
    }
}

function test_get_activity_by_project($project_id) {
    $activity = TeamWorkPm::factory('Activity');
    try {
        $reports = $activity->getByProject($project_id);
        foreach ($reports as $a) {
            print_r($a);
        }
    } catch (Exception $e) {
        print_r($e);
    }
}