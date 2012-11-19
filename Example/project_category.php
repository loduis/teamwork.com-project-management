<?php

require './bootstrap.php';

test_bootstrap(function($command){
    if (in_array($command, array('update', 'get', 'delete'))) {
        return get_first_project_category();
    }
});

function test_insert() {
    $category = TeamWorkPm::factory('Category/Project');
    try {
        echo '------------------TEST INSERT---------------------', "\n";
        $data = array(
          'name'=>'Project category . ' . rand(1, 10)
        );
        // SERIA BUENO SI EL API DEVOLVIERA EL ID DEL PROJECTO
        $id = $category->insert($data);
        echo 'INSERT PROJECT CATEGORY: ', $id, "\n", "\n";
    } catch (Exception $e) {
        print_r($e);
    }
}

function test_update($id) {
    $category = TeamWorkPm::factory('Category/Project');
    try {
        echo '------------------TEST UPDATE---------------------', "\n";
        $data = array(
          'id'=>$id,
          'name'=>'Project category ' . rand(1, 10)
        );
        // SERIA BUENO SI EL API DEVOLVIERA EL ID DEL PROJECTO
        if ($category->update($data)) {
            echo 'UPDATE PROJECT CATEGORY', "\n", "\n";
        }
    } catch (Exception $e) {
        print_r($e);
    }
}


function test_get($id) {
    $category = TeamWorkPm::factory('Category/Project');
    try {
        echo '------------------TEST GET---------------------', "\n";
        $c = $category->get($id);
        print_r($c);
    } catch (Exception $e) {
        print_r($e);
    }
}


function test_get_all() {
    $category = TeamWorkPm::factory('Category/Project');
    try {
        echo '------------------TEST GET BY PROJECT---------------------', "\n";
        $categories = $category->getAll();
        foreach($categories as $c) {
            print_r($c);
        }
    } catch (Exception $e) {
        print_r($e);
    }
}

function test_delete($id) {
    $category = TeamWorkPm::factory('Category/Project');
    try {
        echo '------------------TEST DELETE---------------------', "\n";
        if ($category->delete($id)) {
            echo 'DELETE PROJECT CATEGORY ', $id, "\n";
        }
    } catch (Exception $e) {
        print_r($e);
    }
}