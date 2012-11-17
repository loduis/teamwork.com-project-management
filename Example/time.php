<?php

require './bootstrap.php';

// prepare test
test_bootstrap(function ($command) {
    if (in_array($command,
      array('insert_in_project', 'insert_in_task', 'get_by_project'))) {
        return get_first_project();
    } elseif ($command === 'get' || $command === 'update' || $command === 'delete') {
        return get_first_time();
    } elseif($command === 'get_by_task') {
        return get_first_task();
    }
});


function test_insert_in_project($project_id) {
    $time = TeamWorkPm::factory('Time');
    try {
        echo '------------------TEST INSERT---------------------', "\n";
        $data = array(
          'project_id'=> $project_id,
          'description'=> 'This a time entry.',
          'person_id'=> get_first_people($project_id), # es el id de la persona no el api key
          'date'=> date('Ymd'), # fecha en la que se registra
          'hours'=> rand(1, 12), # horas
          'minutes'=>0,
          'time'=> '08:00',
          'isbillable'=>TRUE
        );
        $id = $time->insert($data);
        echo 'INSERT TIME: ', $id, "\n", "\n";
    } catch (Exception $e) {
        print_r($e);
    }
}

function test_insert_in_task($project_id) {
    $time = TeamWorkPm::factory('Time');
    try {
        echo '------------------TEST INSERT---------------------', "\n";
        $task_id = get_first_task($project_id);
        $data = array(
          'task_id'=> $task_id,
          'description'=> 'This a time entry.',
          'person_id'=> get_first_people($project_id), # es el id de la persona no el api key
          'date'=> date('Ymd'), # fecha en la que se registra
          'hours'=> rand(1, 12), # horas
          'minutes'=>0,
          'time'=> '08:00',
          'isbillable'=>TRUE
        );
        $id = $time->insert($data);
        echo 'INSERT TIME: ', $id, "\n", "\n";
    } catch (Exception $e) {
        print_r($e);
    }
}

function test_get($id) {
    $time = TeamWorkPm::factory('Time');
    try {
        echo '------------------TEST GET---------------------', "\n";
        $entry = $time->get($id);
        print_r($entry);
    } catch(\TeamWorkPm\Exception $e) {
        print_r($e);
    }
}

function test_get_all() {
    $time = TeamWorkPm::factory('Time');
    try {
        echo '------------------TEST GET ALL---------------------', "\n";
        $times = $time->getAll();
        foreach($times as $t) {
            print_r($t);
        }
    } catch(\TeamWorkPm\Exception $e) {
        print_r($e);
    }
}


function test_get_by_project($project_id) {
    $time = TeamWorkPm::factory('Time');
    try {
        echo '------------------TEST GET BY PROJECT---------------------', "\n";
        $times = $time->getByProject($project_id);
        foreach($times as $t) {
            print_r($t);
        }
    } catch(\TeamWorkPm\Exception $e) {
        print_r($e);
    }
}

function test_get_by_task($task_id) {
    $time = TeamWorkPm::factory('Time');
    try {
        echo '------------------TEST GET BY TASK---------------------', "\n";
        $times = $time->getByTask($task_id);
        foreach($times as $t) {
            print_r($t);
        }
    } catch(\TeamWorkPm\Exception $e) {
        print_r($e);
    }
}

/**
 * @todo  fixed update time, si date no se incluye retorna un 500
 * si se incluye el people_id retorna un 401
 * @param type $id
 */
function test_update($entry) {
    $time = TeamWorkPm::factory('Time');
    try {
        echo '------------------TEST UPDATE---------------------', "\n";
        $data = array(
          'id'=> $entry->id,
          'person_id'=> $entry->personId,
          //'description'=> 'This a time entry edit.',
          'date'=> date('Ymd'), # fecha en la que se registra
          'time'=> '08:00',
          'hours'=> rand(1, 12), # horas
          'minutes'=>15,
          'isbillable'=>FALSE
        );
        if ($time->update($data)) {
            echo 'UPDATE TIME: ', $entry->id, "\n", "\n";
        }
    } catch (Exception $e) {
        print_r($e);
    }
}

function test_delete($id) {
    $time = TeamWorkPm::factory('Time');
    try {
        echo '------------------TEST DELETE---------------------', "\n";
        if ($time->delete($id)) {
            echo 'DELETE TIME ', $id, "\n";
        }
    } catch (Exception $e) {
        echo 'Errors: ' , $e->getMessage(), "\n", "\n";
    }
}

