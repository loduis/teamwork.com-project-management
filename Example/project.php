<?php

require './bootstrap.php';

// prepare test
test_boostrap(function ($command) {
    if (in_array($command,
      array('get', 'star', 'unstar', 'update', 'delete', 'archive', 'active'))) {
        return get_first_project();
    }
});


/**
 * Insert one project.
 */
function test_insert() {
    $project = TeamWorkPm::factory('Project');
    try {
        echo '------------------TEST INSERT---------------------', "\n";
        $data = array(
          'name'=>'Test project ' . rand(1, 100),
          'description'=>'This a test project.',
          'new_company'=> 'Esto es una prueba.'
        );
        $id = $project->insert($data);
        echo 'INSERT PROJECT: ', $id, "\n", "\n";
    } catch (Exception $e) {
        print_r($e);
    }
}

/**
 * Get all projects.
 *
 * @return type
 */
function test_get_all() {
    $project = TeamWorkPm::factory('Project');
    try {
        echo '------------------TEST GET ALL---------------------', "\n";
        $projects = $project->getAll();
        $id = NULL;
        foreach ($projects as $p) {
            print_r($p);
            $id = $p->id;
        }
        // save output
        $projects->save('data/projects');
        return $id;
    } catch(Exception $e) {
        //echo 'Errors: ' , $e->getMessage(), "\n", "\n";
        print_r($e);
        return NULL;
    }
}

function test_get_active() {
    $project = TeamWorkPm::factory('Project');
    try {
        echo '------------------TEST GET ACTIVE---------------------', "\n";
        $projects = $project->getActive();
        $id = NULL;
        foreach ($projects as $p) {
            print_r($p);
            $id = $p->id;
        }
    } catch(Exception $e) {
        print_r($e);
    }
}

function test_get_archived() {
    $project = TeamWorkPm::factory('Project');
    try {
        echo '------------------TEST GET ARCIVED---------------------', "\n";
        $projects = $project->getArchived();
        $id = NULL;
        foreach ($projects as $p) {
            print_r($p);
            $id = $p->id;
        }
        // save output
    } catch(Exception $e) {
        print_r($e);
    }
}

function test_get_starred() {
    $project = TeamWorkPm::factory('Project');
    try {
        echo '------------------TEST GET STARRED---------------------', "\n";
        $projects = $project->getStarred();
        $id = NULL;
        foreach ($projects as $p) {
            print_r($p);
            $id = $p->id;
        }
        return $id;
        // save output
    } catch(Exception $e) {
        print_r($e);
    }
}


/**
 * Get one project.
 *
 * @param type $id
 */
function test_get($id) {
    $project = TeamWorkPm::factory('Project');
    try {
        echo '------------------TEST GET---------------------', "\n";
        $p = $project->get($id);
        print_r($p);
    } catch (Exception $e) {
        echo 'Errors: ' , $e->getMessage(), "\n", "\n";
    }
}

/**
 * Update project test.
 *
 * @param int $id
 */
function test_update($id) {
    echo $id, "------------\n";
    $project = TeamWorkPm::factory('Project');
    try {
        echo '------------------TEST UPDATE---------------------', "\n";
        $data = array(
          'id'=>$id,
          'name'=>'Test project edit ' . rand(1, 10),
        );
        if ($project->update($data)) {
            echo 'UPDATE PROJECT', "\n";
        }
    } catch (Exception $e) {
        echo 'Errors: ' , $e->getMessage(), "\n", "\n";
    }
}

function test_star($id) {
    $project = TeamWorkPm::factory('Project');
    try {
        echo '------------------TEST STAR---------------------', "\n";
        if ($project->star($id)) {
            echo 'STAR PROJECT ', $id, "\n";
        }
    } catch (Exception $e) {
        echo 'Errors: ' , $e->getMessage(), "\n", "\n";
    }
}

function test_unstar($id) {
    $project = TeamWorkPm::factory('Project');
    try {
        echo '------------------TEST UNSTAR---------------------', "\n";
        if ($project->unStar($id)) {
            echo 'UNSTAR PROJECT ', $id, "\n";
        }
    } catch (Exception $e) {
        echo 'Errors: ' , $e->getMessage(), "\n", "\n";
    }
}

function test_archived($id) {
    $project = TeamWorkPm::factory('Project');
    try {
        echo '------------------TEST ARCHIVE---------------------', "\n";
        if ($project->archived($id)) {
            echo 'ARCHIVED PROJECT ', $id, "\n";
        }
    } catch (Exception $e) {
        echo 'Errors: ' , $e->getMessage(), "\n", "\n";
    }
}

function test_active($id) {
    $project = TeamWorkPm::factory('Project');
    try {
        echo '------------------TEST ARCHIVE---------------------', "\n";
        if ($project->active($id)) {
            echo 'ACTIVE PROJECT ', $id, "\n";
        }
    } catch (Exception $e) {
        echo 'Errors: ' , $e->getMessage(), "\n", "\n";
    }
}

function test_delete($id) {
    $project = TeamWorkPm::factory('Project');
    try {
        echo '------------------TEST DELETE---------------------', "\n";
        if ($project->delete($id)) {
            echo 'DELETE PROJECT ', $id, "\n";
        }
    } catch (Exception $e) {
        echo 'Errors: ' , $e->getMessage(), "\n", "\n";
    }
}