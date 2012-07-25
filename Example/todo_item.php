<?php

require './bootstrap.php';

test_boostrap(function ($command) {
    if (in_array($command,
      array('get', 'update', 'delete', 'complete'))) {
        return get_first_todo_item();
    } elseif ($command === 'un_complete') {
        return get_first_todo_item_finished();
    } else {
        return get_first_todo_list();
    }
});

function test_insert($todo_list_id) {
    $item = TeamWorkPm::factory('Todo/Item');

    try {
        echo '------------------TEST INSERT---------------------', "\n";
        $data = array(
            'todo_list_id'=> $todo_list_id,
            'content'=>'This is a new task ' . rand(1, 10),
            'description'=> 'Describe...'
        );
        if ($item->insert($data)) {
            echo 'INSERT TODO ITEM', "\n";
        }
    } catch (Exception $e) {
        print_r($e);
    }
}

function test_update($id) {
    $item = TeamWorkPm::factory('Todo/Item');

    try {
        echo '------------------TEST UPDATE---------------------', "\n";
        $data = array(
            'id'=> $id,
            'content'=>'This is a task ' . rand(1, 10),
            'description'=> 'Describe...'
        );
        if ($item->update($data)) {
            echo 'UPDATE TODO ITEM', "\n";
        }
    } catch (Exception $e) {
        print_r($e);
    }
}

function test_get_all_by_task_list($todo_list_id) {
    $item = TeamWorkPm::factory('Todo/Item');

    try {
        echo '------------------TEST GET ALL BY TODO LIST---------------------', "\n";
        $items = $item->getAllByTaskList($todo_list_id);
        foreach ($items as $t) {
            print_r($t);
        }
        $items->save('data/todo_items');
    } catch (Exception $e) {
        print_r($e);
    }
}

function test_get_pending_by_task_list($todo_list_id) {
    $item = TeamWorkPm::factory('Todo/Item');

    try {
        echo '------------------TEST GET PENDING BY TODO LIST---------------------', "\n";
        $items = $item->getPendingByTaskList($todo_list_id);
        foreach ($items as $t) {
            print_r($t);
        }
    } catch (Exception $e) {
        print_r($e);
    }
}

function test_get_upcoming_by_task_list($todo_list_id) {
    $item = TeamWorkPm::factory('Todo/Item');
    try {
        echo '------------------TEST GET UPCOMING BY TODO LIST---------------------', "\n";
        $items = $item->getUpcomingByTaskList($todo_list_id);
        foreach ($items as $t) {
            print_r($t);
        }
    } catch (Exception $e) {
        print_r($e);
    }
}

function test_get_finished_by_task_list($todo_list_id) {
    $item = TeamWorkPm::factory('Todo/Item');
    try {
        echo '------------------TEST GET FINISHED BY TODO LIST---------------------', "\n";
        $items = $item->getFinishedByTaskList($todo_list_id);
        foreach ($items as $t) {
            print_r($t);
        }
    } catch (Exception $e) {
        print_r($e);
    }
}

function test_get_late_by_task_list($todo_list_id) {
    $item = TeamWorkPm::factory('Todo/Item');
    try {
        echo '------------------TEST GET LATE BY TODO LIST---------------------', "\n";
        $items = $item->getLateByTaskList($todo_list_id);
        foreach ($items as $t) {
            print_r($t);
        }
    } catch (Exception $e) {
        print_r($e);
    }
}

function test_get_today_by_task_list($todo_list_id) {
    $item = TeamWorkPm::factory('Todo/Item');
    try {
        echo '------------------TEST GET TODAY BY TODO LIST---------------------', "\n";
        $items = $item->getTodayByTaskList($todo_list_id);
        foreach ($items as $t) {
            print_r($t);
        }
    } catch (Exception $e) {
        print_r($e);
    }
}

function test_get($id) {
    $item = TeamWorkPm::factory('Todo/Item');

    try {
        echo '------------------TEST GET---------------------', "\n";
        $t = $item->get($id);
        print_r($t);
    } catch (Exception $e) {
        print_r($e);
    }
}

/**
 * @param type $todo_list_id
 */
function test_reorder($todo_list_id) {
    $item = TeamWorkPm::factory('Todo/Item');
    try {
        echo '------------------TEST GET---------------------', "\n";
        $ids = array();
        $items = $item->getPendingByTaskList($todo_list_id);
        foreach ($items as $t) {
            $ids[] = (int) $t->id;
        }
        if (!empty($ids)) {
            shuffle($ids);
            $item->reorder($todo_list_id, $ids);
        }

    } catch (Exception $e) {

        print_r($e);
    }
}

function test_complete($id) {
    try {
        $item = TeamWorkPm::factory('Todo/Item');
        echo '------------------TEST COMPLETE---------------------', "\n";
        if ($item->complete($id)) {
            echo 'COMPLETE TODO ITEM ', $id, "\n";
        }
    } catch (Exception $e) {
        echo 'Errors: ' , $e->getMessage(), "\n", "\n";
    }
}

function test_un_complete($id) {
    try {
        $item = TeamWorkPm::factory('Todo/Item');
        echo '------------------TEST UN COMPLETE---------------------', "\n";
        if ($item->unComplete($id)) {
            echo 'UN COMPLETE TODO ITEM ', $id, "\n";
        }
    } catch (Exception $e) {
        echo 'Errors: ' , $e->getMessage(), "\n", "\n";
    }
}


function test_delete($id) {
    $item = TeamWorkPm::factory('Todo/Item');
    try {
        echo '------------------TEST DELETE---------------------', "\n";
        if ($item->delete($id)) {
            echo 'DELETE TODO ITEM ', $id, "\n";
        }
    } catch (Exception $e) {
        echo 'Errors: ' , $e->getMessage(), "\n", "\n";
    }
}