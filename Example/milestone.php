<?php

require './bootstrap.php';

test_bootstrap(function ($command) {
    if (in_array($command,
      array('get', 'update', 'delete', 'complete'))) {
        return get_first_incomplete_milestone();
    } elseif ($command === 'un_complete') {
        return get_first_completed_milestone();
    } elseif($command === 'insert') {
        return get_first_project();
    }
});

function test_insert($project_id) {
    $milestone = TeamWorkPm::factory('Milestone');

    try {
        echo '------------------TEST INSERT---------------------', "\n";
        $data = array(
            'project_id'=> $project_id,
            'title'=>'This is a new milestone ' . rand(1, 10),
            'deadline'=>date('Ymd', strtotime('+2 day')),
            'responsible_party_ids'=> get_first_people($project_id)
        );
        $id = $milestone->insert($data);
        echo 'INSERT MILESTONE: ', $id, "\n";
    } catch (Exception $e) {
        print_r($e);
    }
}

function test_update($id) {
    $milestone = TeamWorkPm::factory('Milestone');

    try {
        echo '------------------TEST UPDATE---------------------', "\n";
        $data = array(
            'id'=> $id,
            'title'=>'This is a edit milestone ' . rand(1, 10),
            'deadline'=>date('Ymd', strtotime('+15 day')),

        );
        if ($milestone->update($data)) {
            echo 'UPDATE MILESTONE ', $id,  "\n";
        }
    } catch (Exception $e) {
        print_r($e);
    }
}

function test_get_all($project_id = NULL) {
    $milestone = TeamWorkPm::factory('Milestone');

    try {
        echo '------------------TEST GET ALL---------------------', "\n";
        $milestones = $milestone->getAll($project_id);
        foreach ($milestones as $m) {
            print_r($m);
        }
        $milestones->save('data/milestones');
    } catch (Exception $e) {
        print_r($e);
    }
}

function test_get_completed($project_id = NULL) {
    $milestone = TeamWorkPm::factory('Milestone');

    try {
        echo '------------------TEST GET COMPLETED---------------------', "\n";
        $milestones = $milestone->getCompleted($project_id);
        foreach ($milestones as $m) {
            print_r($m);
        }
    } catch (Exception $e) {
        print_r($e);
    }
}

function test_get_incomplete($project_id = NULL) {
    $milestone = TeamWorkPm::factory('Milestone');

    try {
        echo '------------------TEST GET INCOMPLETED---------------------', "\n";
        $milestones = $milestone->getIncomplete($project_id);
        foreach ($milestones as $m) {
            print_r($m);
        }
    } catch (Exception $e) {
        print_r($e);
    }
}

function test_get_upcoming($project_id = NULL) {
    $milestone = TeamWorkPm::factory('Milestone');

    try {
        echo '------------------TEST GET UPCOMING---------------------', "\n";
        $milestones = $milestone->getUpcoming($project_id);
        foreach ($milestones as $m) {
            print_r($m);
        }
    } catch (Exception $e) {
        print_r($e);
    }
}

function test_get_late($project_id = NULL) {
    $milestone = TeamWorkPm::factory('Milestone');

    try {
        echo '------------------TEST GET LATE---------------------', "\n";
        $milestones = $milestone->getLate($project_id);
        foreach ($milestones as $m) {
            print_r($m);
        }
    } catch (Exception $e) {
        print_r($e);
    }
}

function test_get($id) {
    $milestone = TeamWorkPm::factory('Milestone');
    try {
        echo '------------------TEST GET---------------------', "\n";
        $m = $milestone->get($id);
        print_r($m);
    } catch (Exception $e) {
        print_r($e);
    }
}

function test_complete($id) {
    try {
        $milestone = TeamWorkPm::factory('Milestone');
        echo '------------------TEST COMPLETE---------------------', "\n";
        if ($milestone->complete($id)) {
            echo 'COMPLETE MILESTONE ', $id, "\n";
        }
    } catch (Exception $e) {
        echo 'Errors: ' , $e->getMessage(), "\n", "\n";
    }
}

function test_un_complete($id) {
    try {
        $milestone = TeamWorkPm::factory('Milestone');
        echo '------------------TEST UN COMPLETE---------------------', "\n";
        if ($milestone->unComplete($id)) {
            echo 'UN COMPLETE MILESTONE ', $id, "\n";
        }
    } catch (Exception $e) {
        echo 'Errors: ' , $e->getMessage(), "\n", "\n";
    }
}


function test_delete($id) {
    $milestone = TeamWorkPm::factory('Milestone');
    try {
        echo '------------------TEST DELETE---------------------', "\n";
        if ($milestone->delete($id)) {
            echo 'DELETE MILESTONE ', $id, "\n";
        }
    } catch (Exception $e) {
        echo 'Errors: ' , $e->getMessage(), "\n", "\n";
    }
}