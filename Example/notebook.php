<?php

require './bootstrap.php';

test_bootstrap(function ($command) {
    if (in_array($command, array('update', 'get', 'delete', 'lock', 'unlock'))) {
        return get_first_notebook();
    } elseif (in_array($command, array('insert', 'get_by_project'))) {
        return get_first_project();
    }
});

function test_insert($project_id) {
    $notebook = TeamWorkPm::factory('notebook');
    try {
        echo '------------------TEST INSERT---------------------', "\n";
        $data = array(
          'project_id'=> $project_id,
          'category_id'=>  get_first_notebook_category($project_id),
          'name'=>'Test notebook',
          'description'=>'Bla bla bla',
          'content'=>'La masamorraa. ..'
        );
        $id = $notebook->insert($data);
        echo 'Insert notebook: ', $id, "\n";
    } catch (\TeamWorkPm\Exception $e) {
        print_r($e);
    }
}
/*
function test_update($id) {
    $notebook = TeamWorkPm::factory('notebook');
    try {
        echo '------------------TEST UPDATE---------------------', "\n";
        $data = array(
          'id'=>$id,
          'description'=>'Bla , bla, ' . rand(1, 10)
        );
        // SERIA BUENO SI EL API DEVOLVIERA EL ID DEL PROJECTO
        if ($notebook->update($data)) {
            echo 'Update notebooks: ', $id, "\n", "\n";
        }
    } catch (Exception $e) {
        //print_r($e);
        echo $e->getMessage();
    }
}*/

function test_get($id) {
    $notebook = TeamWorkPm::factory('notebook');
    try {
        echo '------------------TEST GET---------------------', "\n";
        $c = $notebook->get($id);
        print_r($c);
    } catch (Exception $e) {
        print_r($e);
    }
}

function test_get_all() {
    $notebook = TeamWorkPm::factory('notebook');
    try {
        echo '------------------TEST GET ALL---------------------', "\n";
        $notebooks = $notebook->getAll();
        foreach($notebooks as $m) {
            print_r($m);
        }
    } catch (Exception $e) {
        print_r($e);
    }
}


function test_get_by_project($project_id) {
    $notebook = TeamWorkPm::factory('notebook');
    try {
        echo '------------------TEST GET BY PROJECT---------------------', "\n";
        $notebooks = $notebook->getByProject($project_id);
        foreach($notebooks as $m) {
            print_r($m);
        }
    } catch (Exception $e) {
        print_r($e);
    }
}


function test_delete($id) {
    $notebook = TeamWorkPm::factory('notebook');
    try {
        echo '------------------TEST DELETE---------------------', "\n";
        if ($notebook->delete($id)) {
            echo 'Delete notebook: ', $id, "\n";
        }
    } catch (Exception $e) {
        print_r($e);
    }
}

function test_lock($id) {
    $notebook = TeamWorkPm::factory('notebook');
    try {
        echo '------------------TEST LOCK---------------------', "\n";
        if ($notebook->lock($id)) {
            echo 'Lock notebook: ', $id, "\n";
        }
    } catch (Exception $e) {
        print_r($e);
    }
}

function test_unlock($id) {
    $notebook = TeamWorkPm::factory('notebook');
    try {
        echo '------------------TEST UNLOCK---------------------', "\n";
        if ($notebook->unlock($id)) {
            echo 'Unlock notebook: ', $id, "\n";
        }
    } catch (Exception $e) {
        print_r($e);
    }
}