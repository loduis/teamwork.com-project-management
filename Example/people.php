<?php

require './bootstrap.php';

test_boostrap(function ($command) {
    if ($command === 'delete' || $command === 'get' || $command === 'update') {
        return get_first_people();
    }
});
/**
 * Insert one project.
 */
function test_insert() {
    $people = TeamWorkPm::factory('People');
    try {
        echo '------------------TEST INSERT---------------------', "\n";
        $data = array(
            'first_name'=> 'Lucas',
            'last_name'=>'Madariaga',
            'email_address'=>'loduis@hotmail.com',
            'user_name'=>'lucas' .  rand(1, 10),
            'password'=>'loquis81',
            'title'=>'Mr',
            'send_welcome_email'=>'no'
        );

        if ($id = $people->insert($data)) {
            echo 'INSERT PEOPLE: ', $id, "\n", "\n";
        }
    } catch (TeamWorkPm_Exception $e) {
        echo 'Errors: ' , $e->getMessage(), "\n";
        echo 'Reponse: ' , $e->getResponse(), "\n";
        print_r($e->getHeaders());
    }
}

function test_update($id) {
    $people = TeamWorkPm::factory('People');
    try {
        echo '------------------TEST UPDATE---------------------', "\n";
        $data = array(
            'id'=> $id,
            'first_name'=> 'Lucas' . rand(1, 10),
            'last_name'=>'Madariaga',
            'title'=>'Mr'
        );
        if ($people->update($data)) {
            echo 'INSERT UPDATE', "\n", "\n";
        }
    } catch (TeamWorkPm_Exception $e) {
        echo 'Errors: ' , $e->getMessage(), "\n";
        echo 'Reponse: ' , $e->getResponse(), "\n";
        print_r($e->getHeaders());
    }
}

function test_get_all() {
    $people = TeamWorkPm::factory('People');
    try {
        echo '------------------TEST GET ALL---------------------', "\n";
        $rows = $people->getAll();
        foreach ($rows as $p) {
            print_r($p);
        }
    } catch (TeamWorkPm_Exception $e) {
        print_r($e);
    }
}

function test_get($id) {
    $people = TeamWorkPm::factory('People');
    try {
        echo '------------------TEST GET---------------------', "\n";
        $p = $people->get($id);
        print_r($p);
    } catch (TeamWorkPm_Exception $e) {
        print_r($e);
    }
}

function test_delete($id) {
    $people = TeamWorkPm::factory('People');
    try {
        echo '------------------TEST DELETE---------------------', "\n";
        if ($people->delete($id)) {
            echo 'DELETE PEOPLE ', $id, "\n";
        }
    } catch (TeamWorkPm_Exception $e) {
        echo 'Errors: ' , $e->getMessage(), "\n";
        echo 'Reponse: ' , $e->getResponse(), "\n";
        print_r($e->getHeaders());
    }
}