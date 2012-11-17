<?php

require './bootstrap.php';

test_bootstrap(function ($command) {
    if ($command === 'add_to_project' || $command === 'get_by_project') {
        return get_first_project();
    } elseif ($command === 'get' || $command === 'delete') {
        return get_first_file();
    }
});

function test_add_to_project($project_id) {
    try {
        echo '------------------TEST ADD TO PROJECT---------------------', "\n";
        $file  = TeamWorkPm::factory('File');
        $file_id = $file->addToProject(array(
          'project_id'    => $project_id,
          'filename'      => __DIR__ . '/uploads/teamworkpm.jpg',
          'description'   => 'This an description',
          'category_id' =>2605
          //'category_name'=>'File category'
        ));
        if ($file_id) {
            echo 'UPLOAD FILE: ', $file_id, ' to project ', $project_id;
        }
    } catch (Exception $e) {
        print_r($e);
    }
}

function test_get_by_project($project_id) {
    $file = TeamWorkPm::factory('File');
    try {
        $files = $file->getByProject($project_id);
        foreach ($files as $f) {
            print_r($f);
        }
    } catch (\TeamWorkPm\Exception $e) {
        print_r($e);
    }
}

function test_get($id) {
    $file = TeamWorkPm::factory('File');
    try {
        echo '------------------TEST GET---------------------', "\n";
        $f = $file->get($id);
        print_r($f);
    } catch (Exception $e) {
        echo 'Errors: ' , $e->getMessage(), "\n", "\n";
    }
}


function test_delete($id) {
    $file = TeamWorkPm::factory('File');
    try {
        echo '------------------TEST DELETE---------------------', "\n";
        if ($file->delete($id)) {
            echo 'DELETE FILE ', $id, "\n";
        }
    } catch (Exception $e) {
        echo 'Errors: ' , $e->getMessage(), "\n", "\n";
    }
}
