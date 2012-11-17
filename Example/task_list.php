<?php

require './bootstrap.php';

test_bootstrap(function ($command) {
    if (in_array($command,
      array('get', 'update', 'delete', 'complete'))) {
        return get_first_task_list();
    } elseif ($command === 'un_complete') {
        return get_first_completed_task_list();
    } else {
        return get_first_project();
    }
});

function test_insert($project_id) {
    $list = TeamWorkPm::factory('Task/List');

    try {
        $data = array(
            'project_id'=> $project_id,
            'name'=>'This is a new task list ' . rand(1, 10),
            'description'=> 'Describe...',
            'pinned'=> true
        );
        $id = $list->insert($data);
        echo 'INSERT TASK LIST ', $id,  "\n";
    } catch (Exception $e) {
        print_r($e);
    }
}

function test_update($id) {
    $list = TeamWorkPm::factory('Task/List');

    try {
        echo '------------------TEST UPDATE---------------------', "\n";
        $data = array(
            'id'=> $id,
            'name'=>'This is a new task list ' . rand(1, 10),
            'description'=> 'Describe...'
        );
        if ($list->update($data)) {
            echo 'UPDATE TASK LIST', $id, "\n";
        } else {
            echo 'CAN NOT UPDATE TASK LIST', $id, "\n";
        }
    } catch (Exception $e) {
        print_r($e);
    }
}

function test_get_all_by_project($project_id) {
    $list = TeamWorkPm::factory('Task/List');

    try {
        echo '------------------TEST GET ALL BY PROJECT---------------------', "\n";
        $taks = $list->getAllByProject($project_id);
        foreach ($taks as $t) {
            print_r($t);
        }
        $taks->save('data/task_lists');
    } catch (Exception $e) {
        print_r($e);
    }
}

function test_get_active_by_project($project_id) {
    $list = TeamWorkPm::factory('Task/List');

    try {
        echo '------------------TEST GET ACTIVE BY PROJECT---------------------', "\n";
        $taks = $list->getActiveByProject($project_id);
        foreach ($taks as $t) {
            print_r($t);
        }
    } catch (Exception $e) {
        print_r($e);
    }
}

function test_get_completed_by_project($project_id) {
    $list = TeamWorkPm::factory('Task/List');

    try {
        echo '------------------TEST GET COMPLETED BY PROJECT---------------------', "\n";
        $taks = $list->getActiveByProject($project_id);
        foreach ($taks as $t) {
            print_r($t);
        }
    } catch (Exception $e) {
        print_r($e);
    }
}


function test_get($id) {
    $list = TeamWorkPm::factory('Task/List');

    try {
        echo '------------------TEST GET---------------------', "\n";
        $l = $list->get($id);
        print_r($l);
    } catch (Exception $e) {
        print_r($e);
    }
}
/**
 * @todo  Fixed return invalid parameters
 * @param type $project_id
 */

function test_reorder($project_id) {
    $list = TeamWorkPm::factory('Task/List');
    try {
        echo '------------------TEST REORDER---------------------', "\n";
        $ids = array();
        $lists = $list->getActiveByProject($project_id);
        foreach ($lists as $l) {
            $ids[] = (int) $l->id;
        }
        if (!empty($ids)) {
            shuffle($ids);
            $list->reorder($project_id, $ids);
        }

    } catch (Exception $e) {

        print_r($e);
    }
}

function test_delete($id) {
    $list = TeamWorkPm::factory('Task/List');
    try {
        if ($list->delete($id)) {
            echo 'DELETE TASK LIST: ', $id, "\n";
        } else {
            echo 'CAN NOT DELETE TASK LIST: ', $id, "\n";
        }
    } catch (Exception $e) {
        echo 'Errors: ' , $e->getMessage(), "\n", "\n";
    }
}