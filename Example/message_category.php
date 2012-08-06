<?php

require './bootstrap.php';

test_boostrap(function($command){
    if (in_array($command, array('update', 'get', 'delete'))) {
        return get_first_message_category();
    } elseif (in_array($command, array('insert', 'get_by_project'))) {
        return get_first_project();
    }
});

function test_insert($project_id) {
    $category = TeamWorkPm::factory('Category/Message');
    try {
        echo '------------------TEST INSERT---------------------', "\n";
        $data = array(
          'project_id'=> $project_id,
          'name'=>'Message category'
        );
        // SERIA BUENO SI EL API DEVOLVIERA EL ID DEL PROJECTO
        $id = $category->insert($data);
        echo 'INSERT MESSAGE CATEGORY: ', $id, "\n", "\n";
    } catch (Exception $e) {
        print_r($e);
    }
}

function test_update($id) {
    $category = TeamWorkPm::factory('Category/Message');
    try {
        echo '------------------TEST UPDATE---------------------', "\n";
        $data = array(
          'id'=>$id,
          'name'=>'Message category ' . rand(1, 10)
        );
        // SERIA BUENO SI EL API DEVOLVIERA EL ID DEL PROJECTO
        if ($category->update($data)) {
            echo 'UPDATE MESSAGE CATEGORY', "\n", "\n";
        }
    } catch (Exception $e) {
        print_r($e);
    }
}


function test_get($id) {
    $category = TeamWorkPm::factory('Category/Message');
    try {
        echo '------------------TEST GET---------------------', "\n";
        $c = $category->get($id);
        print_r($c);
    } catch (Exception $e) {
        print_r($e);
    }
}


function test_get_by_project($project_id) {
    $category = TeamWorkPm::factory('Category/Message');
    try {
        echo '------------------TEST GET BY PROJECT---------------------', "\n";
        $categories = $category->getByProject($project_id);
        foreach($categories as $c) {
            print_r($c);
        }
    } catch (Exception $e) {
        print_r($e);
    }
}

function test_delete($id) {
    $category = TeamWorkPm::factory('Category/Message');
    try {
        echo '------------------TEST DELETE---------------------', "\n";
        if ($category->delete($id)) {
            echo 'DELETE MESSAGE CATEGORY ', $id, "\n";
        }
    } catch (Exception $e) {
        print_r($e);
    }
}