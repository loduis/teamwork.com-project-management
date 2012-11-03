<?php

require './bootstrap.php';

test_boostrap(function ($command) {
    if (in_array($command, array('get_by_project', 'insert'))) {
        return get_first_project();
    } elseif (in_array($command, array('get', 'update', 'delete'))) {
        return get_first_link();
    }
});

function test_get_all()
{
    $link = TeamWorkPm::factory('link');
    try {
        $projects_links = $link->getAll();
        //print_r($projects_links);
        foreach ($projects_links as $project) {
            echo $project->id, "\n";
            foreach ($project->links as $link) {
                echo $link->name, "\n";
            }
        }
    } catch (Exception $e) {
        print_r($e);
    }
}

function test_get_by_project($id)
{
    $link = TeamWorkPm::factory('link');
    try {
        $links = $link->getByProject($id);
        print_r($links);
    } catch (Exception $e) {
        print_r($e);
    }
}

function test_get($id)
{
    $link = TeamWorkPm::factory('link');
    try {
        $link = $link->get($id);
        print_r($link);
    } catch (Exception $e) {
        print_r($e);
    }
}

function test_insert($project_id)
{
    $link = TeamWorkPm::factory('link');
    try {
        $data = array(
            'project_id'=> $project_id,
            'name'=> 'Link ' . rand(1, 100),
            'height'=> 300,
            'width'=> 100,
            'category_name'=> 'Es una prueba',
            'code'=> 'www.oneurl.com'
        );
        $id = $link->insert($data);
        echo 'INSERT LINK ID: ', $id, "\n";
    } catch (Exception $e) {
        print_r($e);
    }
}


function test_update($id)
{
    $link = TeamWorkPm::factory('link');
    try {
        $data = array(
            'id'=> $id,
            'name'=> 'Link update ' . rand(1, 100),
            'height'=> 300,
            'width'=> 300,
            'category_name'=> 'Es una prueba 2',
            'code'=> 'www.oneurl.com'
        );
        if ($link->update($data)) {
            echo 'UPDATE LINK ID: ', $id, "\n";
        }
    } catch (Exception $e) {
        print_r($e);
    }
}

function test_delete($id) {
    $link = TeamWorkPm::factory('link');
    try {
        if ($link->delete($id)) {
            echo 'DELETE LINK: ', $id, "\n";
        }
    } catch (Exception $e) {
        print_r($e);
    }
}
