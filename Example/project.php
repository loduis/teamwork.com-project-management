<?php

require './bootstrap.php';

// prepare test
test_bootstrap(function ($command) {
    if (in_array($command,
      array('get', 'star', 'unstar', 'update', 'delete', 'archive', 'activate'))) {
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
          'company_id'=> 9683,
          'start_date'=> date('Ymd', strtotime('+1 day')),
          'end_date'=> date('Ymd', strtotime('+4 day')),
          'announcement'=> 'This is field announcement',
          'show_announcement'=> true,
          'notifyeveryone'=> true

        );

        // set project to category
        $data['category_id'] = get_first_project_category();
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
            echo $p->id, "=", $p->name, "\n";
        }
        // save output
        $projects->save('data/projects');
    } catch(Exception $e) {
        print_r($e);
    }
}

function test_get_active() {
    $project = TeamWorkPm::factory('Project');
    try {
        echo '------------------TEST GET ACTIVE---------------------', "\n";
        $projects = $project->getActive();
        foreach ($projects as $p) {
            echo $p->id, "=", $p->name, "\n";
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

function test_archive($id) {
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

function test_activate($id) {
    $project = TeamWorkPm::factory('Project');
    try {
        echo '------------------TEST ACTIVATE---------------------', "\n";
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