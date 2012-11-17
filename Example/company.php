<?php

require './bootstrap.php';

test_bootstrap(function($command){
    if (in_array($command, array('update', 'get', 'delete'))) {
        return get_first_company();
    } elseif ($command === 'get_by_project') {
        return get_first_project();
    }
});

function test_insert() {
    $company = TeamWorkPm::factory('Company');
    try {
        echo '------------------TEST INSERT---------------------', "\n";
        $data = array(
          'name'=>'phpapi - ' . rand(1, 10),
          'address_one'=> 'Address one'
        );
        // SERIA BUENO SI EL API DEVOLVIERA EL ID DEL PROJECTO
        if ($company->insert($data)) {
            echo 'INSERT COMPANY', "\n", "\n";
        }
    } catch (Exception $e) {
        echo 'Errors: ' , $e->getMessage(), "\n";
    }
}

function test_update($id) {
    $company = TeamWorkPm::factory('Company');
    try {
        echo '------------------TEST UPDATE---------------------', "\n";
        $data = array(
          'id'=>$id,
          'name'=>'Test company edit ' . rand(1, 10),
          'address_one'=> 'Address one',
          'address_two'=> 'Address two'
        );
        // SERIA BUENO SI EL API DEVOLVIERA EL ID DEL PROJECTO
        if ($company->update($data)) {
            echo 'UPDATE COMPANY', "\n", "\n";
        }
    } catch (Exception $e) {
        echo 'Errors: ' , $e->getMessage(), "\n";
    }
}


function test_get($id) {
    $company = TeamWorkPm::factory('Company');
    try {
        echo '------------------TEST GET---------------------', "\n";
        $c = $company->get($id);
        print_r($c);
    } catch (Exception $e) {
        echo 'Errors: ' , $e->getMessage(), "\n";
    }
}

function test_get_all() {
    $company = TeamWorkPm::factory('Company');
    try {
        echo '------------------TEST GET ALL---------------------', "\n";
        $companies = $company->getAll();
        foreach($companies as $c) {
            echo $c->id, "=", $c->name, "\n";
        }
    } catch (Exception $e) {
        echo 'Errors: ' , $e->getMessage(), "\n";
    }
}

function test_get_by_project($project_id) {
    $company = TeamWorkPm::factory('Company');
    try {
        echo '------------------TEST GET ALL BY PROJECT---------------------', "\n";
        $companies = $company->getByProject($project_id);
        foreach($companies as $c) {
            print_r($c);
        }
    } catch (Exception $e) {
        echo 'Errors: ' , $e->getMessage(), "\n";
    }
}

function test_delete($id) {
    $company = TeamWorkPm::factory('Company');
    try {
        echo '------------------TEST DELETE---------------------', "\n";
        if ($company->delete($id)) {
            echo 'DELETE COMPANY ', $id, "\n";
        }
    } catch (Exception $e) {
        echo 'Errors: ' , $e->getMessage(), "\n", "\n";
    }
}
