<?php

require './bootstrap.php';

test_bootstrap(function ($command) {
    if ($command === 'delete' || $command === 'get' || $command === 'update') {
        return get_first_people();
    } elseif ($command === 'insert') {
        return get_first_project();
    }
});
/**
 * Insert one project.
 */
function test_insert($project_id) {
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
        // add user to project
        $data['project_id'] = $project_id;
        // add permissions
        $data['permissions'] = array(
            'view_messages_and_files' => 0
        );

        if ($id = $people->insert($data)) {
            echo 'INSERT PEOPLE: ', $id, "\n", "\n";
        }
    } catch (\TeamWorkPm\Exception $e) {
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
        $project_id         =  get_first_project();
        $data['project_id'] = $project_id;
        $data['permissions'] = array(
          'view_messages_and_files' => 1,
          'view_tasks_and_milestones' => 0
        );
        if ($people->update($data)) {
            echo 'UPDATE PEOPLE: ', $id, "\n";
        }
    } catch (\TeamWorkPm\Exception $e) {
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
    } catch (\TeamWorkPm\Exception $e) {
        print_r($e);
    }
}

function test_get($id) {
    $people = TeamWorkPm::factory('People');
    try {
        echo '------------------TEST GET---------------------', "\n";
        $p = $people->get($id);
        print_r($p);
    } catch (\TeamWorkPm\Exception $e) {
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
    } catch (\TeamWorkPm\Exception $e) {
        echo 'Errors: ' , $e->getMessage(), "\n";
        echo 'Reponse: ' , $e->getResponse(), "\n";
        print_r($e->getHeaders());
    }
}