<?php

require './bootstrap.php';

// prepare test
test_bootstrap(function ($command) {
    if (in_array($command, array('update', 'delete', 'get'))) {
        return get_first_people_status();
    } elseif ($command === 'insert') {
        return get_first_people();
    }
});


function test_insert($person_id)
{
    $people_status = TeamWorkPm::factory('people/status');
    try {
        $data = array(
          'person_id' => $person_id,
          'status'=> 'Esto es un status of the test.'
        );
        $id = $people_status->insert($data);
        echo "PEOPLE STATUS INSERT: $id";
    } catch (Exception $e) {
        print_r($e);
    }
}

function test_update($id)
{
    $people_status = TeamWorkPm::factory('people/status');
    try {
        $data = array(
          'id' => $id,
          'status'=> 'Update this status ....'
        );
        if ($people_status->update($data)) {
            echo "PEOPLE STATUS UPDATE: $id";
        } else {
            echo "CAN NOT PEOPLE STATUS UPDATE: $id";
        }
    } catch (Exception $e) {
        print_r($e);
    }
}

function test_get($id)
{
    $people_status = TeamWorkPm::factory('people/status');
    try {
        $people_status = $people_status->get($id);
        print_r($people_status);
    } catch (Exception $e) {
        print_r($e);
    }
}

function test_get_all()
{
    $people_status = TeamWorkPm::factory('people/status');
    try {
        $people_status = $people_status->getAll();
        foreach ($people_status as $status) {
            print_r($people_status);
        }
    } catch (Exception $e) {
        print_r($e);
    }
}


function test_delete($id)
{
    $people_status = TeamWorkPm::factory('people/status');
    try {
        if ($id && $people_status->delete($id)) {
            echo "DELETE PEOPLE STATUS: $id\n";
        } else {
            echo "CAN NOT DELETE PEOPLE STATUS: $id\n";
        }
    } catch (Exception $e) {
        print_r($e);
    }
}


function get_first_people_status()
{
    $people_status = TeamWorkPm::factory('people/status');
    $people_status = $people_status->getAll();
    foreach ($people_status as $status) {
        return $status->id;
    }
}