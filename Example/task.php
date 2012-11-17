<?php

require './bootstrap.php';

test_bootstrap(function ($command) {
    if (in_array($command,
      array('get', 'update', 'delete', 'complete'))) {
        return get_first_task();
    } elseif ($command === 'un_complete') {
        return get_first_task_finished();
    } else {
        return get_first_task_list();
    }
});

function test_insert($task_list_id) {
    $item = TeamWorkPm::factory('task');
    $list = TeamWorkPm::factory('task/list');
    try {
        $list       = $list->get($task_list_id);
        $project_id = $list->projectId;
        $people_id  = get_first_people($project_id);
        $data = array(
            'task_list_id'         => $task_list_id,
            'content'              =>'This is a new task ' . rand(1, 10),
            'description'          => 'Describe...',
            'due_date'             => date('Ymd', strtotime('+5 days')),
            'start_date'           => date('Ymd'),
            'private'              => false,
            'priority'             => 'high',
            'estimated_minutes'    => 300,
            'responsible_party_id' => $people_id
        );
        $id = $item->insert($data);
        echo 'INSERT TASK: ', $id, "\n";
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

function test_update($id) {
    $item = TeamWorkPm::factory('task');
    try {
        echo '------------------TEST UPDATE---------------------', "\n";
        $data = array(
            'id'=> $id,
            'content'=>'This is a task - update - ' . rand(1, 10),
            'description'=> 'Describe...'
        );
        if ($item->update($data)) {
            echo 'UPDATE TASK ', $id, "\n";
        }
    } catch (Exception $e) {
        print_r($e);
    }
}

function test_get_all_by_task_list($task_list_id) {
    $item = TeamWorkPm::factory('Task');

    try {
        echo '------------------TEST GET ALL BY TODO LIST---------------------', "\n";
        $items = $item->getAllByTaskList($task_list_id);
        foreach ($items as $t) {
            print_r($t);
        }
        $items->save('data/tasks');
    } catch (Exception $e) {
        print_r($e);
    }
}

function test_get_pending_by_task_list($task_list_id) {
    $item = TeamWorkPm::factory('Task');

    try {
        echo '------------------TEST GET PENDING BY TODO LIST---------------------', "\n";
        $items = $item->getPendingByTaskList($task_list_id);
        foreach ($items as $t) {
            print_r($t);
        }
    } catch (Exception $e) {
        print_r($e);
    }
}

function test_get_upcoming_by_task_list($task_list_id) {
    $item = TeamWorkPm::factory('Task');
    try {
        echo '------------------TEST GET UPCOMING BY TODO LIST---------------------', "\n";
        $items = $item->getUpcomingByTaskList($task_list_id);
        foreach ($items as $t) {
            print_r($t);
        }
    } catch (Exception $e) {
        print_r($e);
    }
}

function test_get_finished_by_task_list($task_list_id) {
    $item = TeamWorkPm::factory('Task');
    try {
        echo '------------------TEST GET FINISHED BY TODO LIST---------------------', "\n";
        $items = $item->getFinishedByTaskList($task_list_id);
        foreach ($items as $t) {
            print_r($t);
        }
    } catch (Exception $e) {
        print_r($e);
    }
}

function test_get_late_by_task_list($task_list_id) {
    $item = TeamWorkPm::factory('Task');
    try {
        echo '------------------TEST GET LATE BY TODO LIST---------------------', "\n";
        $items = $item->getLateByTaskList($task_list_id);
        foreach ($items as $t) {
            print_r($t);
        }
    } catch (Exception $e) {
        print_r($e);
    }
}

function test_get_today_by_task_list($task_list_id) {
    $item = TeamWorkPm::factory('Task');
    try {
        echo '------------------TEST GET TODAY BY TODO LIST---------------------', "\n";
        $items = $item->getTodayByTaskList($task_list_id);
        foreach ($items as $t) {
            print_r($t);
        }
    } catch (Exception $e) {
        print_r($e);
    }
}

function test_get($id) {
    $item = TeamWorkPm::factory('Task');

    try {
        echo '------------------TEST GET---------------------', "\n";
        $t = $item->get($id);
        //print_r($t);
        echo $t;
    } catch (Exception $e) {
        print_r($e);
    }
}

/**
 * @param type $task_list_id
 */
function test_reorder($task_list_id) {
    $item = TeamWorkPm::factory('Task');
    try {
        echo '------------------TEST GET---------------------', "\n";
        $ids = array();
        $items = $item->getPendingByTaskList($task_list_id);
        foreach ($items as $t) {
            $ids[] = (int) $t->id;
        }
        if (!empty($ids)) {
            shuffle($ids);
            $item->reorder($task_list_id, $ids);
        }

    } catch (Exception $e) {

        print_r($e);
    }
}

function test_complete($id) {
    try {
        $item = TeamWorkPm::factory('Task');
        echo '------------------TEST COMPLETE---------------------', "\n";
        if ($item->complete($id)) {
            echo 'COMPLETE TASK ', $id, "\n";
        }
    } catch (Exception $e) {
        echo 'Errors: ' , $e->getMessage(), "\n", "\n";
    }
}

function test_un_complete($id) {
    try {
        $item = TeamWorkPm::factory('Task');
        echo '------------------TEST UN COMPLETE---------------------', "\n";
        if ($item->unComplete($id)) {
            echo 'UN COMPLETE TASK ', $id, "\n";
        }
    } catch (Exception $e) {
        echo 'Errors: ' , $e->getMessage(), "\n", "\n";
    }
}


function test_delete($id) {
    $item = TeamWorkPm::factory('Task');
    try {
        echo '------------------TEST DELETE---------------------', "\n";
        if ($item->delete($id)) {
            echo 'DELETE TASK ', $id, "\n";
        }
    } catch (Exception $e) {
        echo 'Errors: ' , $e->getMessage(), "\n", "\n";
    }
}