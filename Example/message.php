<?php

require './bootstrap.php';

test_bootstrap(function ($command) {
    if (in_array($command, array('update', 'get', 'delete'))) {
        return get_first_message();
    } elseif (in_array($command, array('insert', 'get_by_project', 'get_by_project_and_category'))) {
        return get_first_project();
    }
});

function test_insert($project_id) {
    $message = TeamWorkPm::factory('Message');
    try {
        echo '------------------TEST INSERT---------------------', "\n";
        $file = TeamWorkPm::factory('File');
        $filename = __DIR__ . '/uploads/person.png';
        $pending_file_ref = $file->upload($filename);
        $data = array(
          'project_id'=> $project_id,
          'category_id'=>  get_first_message_category($project_id),
          'title'=>'Test message',
          'body'=>'Bla bla bla',
          'pending_file_attachments'=> $pending_file_ref
        );
        $id = $message->insert($data);
        echo 'INSERT MESSAGE: ', $id, "\n";
    } catch (\TeamWorkPm\Exception $e) {
        print_r($e);
    }
}

function test_update($id) {
    $message = TeamWorkPm::factory('Message');
    try {
        echo '------------------TEST UPDATE---------------------', "\n";
        $data = array(
          'id'=>$id,
          'body'=>'Bla , bla, update ' . rand(1, 10)
        );
        // SERIA BUENO SI EL API DEVOLVIERA EL ID DEL PROJECTO
        if ($message->update($data)) {
            echo 'UPDATE MESSAGE: ', $id, "\n";
        }
    } catch (Exception $e) {
        print_r($e);
    }
}

function test_get($id) {
    $message = TeamWorkPm::factory('Message');
    try {
        echo '------------------TEST GET---------------------', "\n";
        $c = $message->get($id);
        print_r($c);
    } catch (Exception $e) {
        print_r($e);
    }
}


function test_get_by_project($project_id) {
    $message = TeamWorkPm::factory('Message');
    try {
        echo '------------------TEST GET BY PROJECT---------------------', "\n";
        $messages = $message->getByProject($project_id);
        foreach($messages as $m) {
            print_r($m);
        }
    } catch (Exception $e) {
        print_r($e);
    }
}

function test_get_by_project_and_category($project_id) {
    $message = TeamWorkPm::factory('Message');
    try {
        echo '------------------TEST GET BY PROJECT AND CATEGORY---------------------', "\n";
        $category_id = get_first_message_category($project_id);
        $messages    = $message->getByProjectAndCategory($project_id, $category_id);
        foreach($messages as $m) {
            print_r($m);
        }
    } catch (Exception $e) {
        print_r($e);
    }
}


function test_delete($id) {
    $message = TeamWorkPm::factory('Message');
    try {
        echo '------------------TEST DELETE---------------------', "\n";
        if ($message->delete($id)) {
            echo 'DELETE MESSAGE ', $id, "\n";
        }
    } catch (Exception $e) {
        print_r($e);
    }
}