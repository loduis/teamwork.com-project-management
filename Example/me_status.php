<?php

require './bootstrap.php';

// prepare test
test_bootstrap(function ($command) {
    if (in_array($command, array('update', 'delete'))) {
        return get_first_me_status();
    }
});


function test_insert()
{
    $me_status = TeamWorkPm::factory('me/status');
    try {
        $data = array(
          'status'=> 'Esto es un status of the test.'
        );
        $id = $me_status->insert($data);
        echo "ME STATUS INSERT: $id";
    } catch (Exception $e) {
        print_r($e);
    }
}

function test_update($id)
{
    $me_status = TeamWorkPm::factory('me/status');
    try {
        $data = array(
          'id' => $id,
          'status'=> 'Update this status ....'
        );
        if ($me_status->update($data)) {
            echo "ME STATUS UPDATE: $id";
        } else {
            echo "CAN NOT ME STATUS UPDATE: $id";
        }
    } catch (Exception $e) {
        print_r($e);
    }
}

function test_get()
{
    $me_status = TeamWorkPm::factory('me/status');
    try {
        $me_status = $me_status->get();
        print_r($me_status);
    } catch (Exception $e) {
        print_r($e);
    }
}

function test_delete($id)
{
    $me_status = TeamWorkPm::factory('me/status');
    try {
        if ($id && $me_status->delete($id)) {
            echo "DELETE ME STATUS: $id\n";
        } else {
            echo "CAN NOT DELETE ME STATUS: $id\n";
        }
    } catch (Exception $e) {
        print_r($e);
    }
}


function get_first_me_status()
{
    $me_status = TeamWorkPm::factory('me/status');
    $status = $me_status->get();

    return empty($status->id) ? null : $status->id;
}