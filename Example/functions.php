<?php

function get_first_project()
{
    $project = TeamWorkPm::factory('Project');
    foreach ($project->getActive() as $p) {
        return $p->id;
    }
}

function null_return($command) {
      return NULL;
}

function test_bootstrap($callback = 'null_return') {
    $argc = $_SERVER['argc'];
    $argv = $_SERVER['argv'];
    if ($argc >= 2) {
        $command = $argv[1];
        $function = 'test_' . $command;
        if (function_exists($function)) {
            $id = empty($argv[2]) ? $callback($command) : $argv[2];
            $function($id);
        }
    }
}

function get_first_task_list($project_id = NULL)
{
    $project_id = get_project_id_is_null($project_id);
    $list = TeamWorkPm::factory('Task/List');
    $lists = $list->getActiveByProject($project_id);
    foreach ($lists as $l) {
        return $l->id;
    }
}

function get_first_task($project_id = NULL) {
    $project_id = get_project_id_is_null($project_id);
    $task_list_id =  get_first_task_list($project_id);
    $item = TeamWorkPm::factory('Task');
    $items = $item->getPendingByTaskList($task_list_id);
    foreach ($items as $i) {
        return (int) $i->id;
    }
}

function get_first_time() {
    $time = TeamWorkPm::factory('Time');
    $times = $time->getAll();
    foreach ($times as $t) {
        return $t;
    }
}

function get_project_id_is_null($project_id) {
    if ($project_id === NULL) {
        $project_id = get_first_project();
    }

    return $project_id;
}

function get_first_task_finished() {
    $task_list_id =  get_first_task_list();
    $item = TeamWorkPm::factory('Task');
    $items = $item->getFinishedByTaskList($task_list_id);
    foreach ($items as $i) {
        return (int) $i->id;
    }
}

function get_first_completed_task_list()
{
    $project_id = get_first_project();
    $list = TeamWorkPm::factory('Task/List');
    $lists = $list->getCompletedByProject($project_id);
    foreach ($lists as $l) {
        return $l->id;
    }
}

function get_first_company() {
    $company = TeamWorkPm::factory('Company');
    $companies = $company->getAll();
    foreach($companies as $c) {
        return (int) $c->id;
    }
}

function get_first_people($project_id = NULL) {
    $people = TeamWorkPm::factory('People');
    $peoples = $project_id ? $people->getByProject($project_id) : $people->getAll();
    foreach($peoples as $p) {
        return (int) $p->id;
    }
}

function get_first_incomplete_milestone() {
    $milestone = TeamWorkPm::factory('Milestone');
    $milestones = $milestone->getIncomplete();
    foreach($milestones as $m) {
        return (int) $m->id;
    }
}

function get_first_completed_milestone() {
    $milestone = TeamWorkPm::factory('Milestone');
    $milestones = $milestone->getCompleted();
    foreach($milestones as $m) {
        return (int) $m->id;
    }
}

function get_first_file() {
    $project_id = get_first_project();
    $file = TeamWorkPm::factory('File');
    try {
        $files = $file->getByProject($project_id);
        foreach ($files as $f) {
            return (int) $f->id;
        }
    } catch (\TeamWorkPm\Exception $e) {
        print_r($e);
    }
}

function get_first_link() {
    $project_id = get_first_project();
    $link = TeamWorkPm::factory('link');
    try {
        $links = $link->getByProject($project_id);
        foreach ($links as $l) {
            return (int) $l->id;
        }
    } catch (\TeamWorkPm\Exception $e) {
        print_r($e);
    }
}


function get_first_message_category($project_id = NULL) {
    $project_id = get_project_id_is_null($project_id);
    $category = TeamWorkPm::factory('Category/Message');
    $categories = $category->getByProject($project_id);
    foreach ($categories as $c) {
        return (int) $c->id;
    }
}

function get_first_notebook_category($project_id = NULL) {
    $project_id = get_project_id_is_null($project_id);
    $category = TeamWorkPm::factory('category/notebook');
    $categories = $category->getByProject($project_id);
    foreach ($categories as $c) {
        return (int) $c->id;
    }
}

function get_first_link_category($project_id = NULL) {
    $project_id = get_project_id_is_null($project_id);
    $category = TeamWorkPm::factory('category/link');
    $categories = $category->getByProject($project_id);
    foreach ($categories as $c) {
        return (int) $c->id;
    }
}

function get_first_file_category($project_id = NULL) {
    $project_id = get_project_id_is_null($project_id);
    $category = TeamWorkPm::factory('category/file');
    $categories = $category->getByProject($project_id);
    foreach ($categories as $c) {
        return (int) $c->id;
    }
}


function get_first_message($project_id = NULL) {
    $project_id = get_project_id_is_null($project_id);
    $message = TeamWorkPm::factory('Message');
    try {
        $messages = $message->getByProject($project_id);
        foreach ($messages as $m) {
            return (int) $m->id;
        }
    } catch (\TeamWorkPm\Exception $e) {
        print_r($e);
    }
}

function get_first_notebook() {
    $project_id = get_first_project();
    $notebook = TeamWorkPm::factory('notebook');
    try {
        $notebooks = $notebook->getByProject($project_id);
        foreach ($notebooks as $n) {
            return (int) $n->id;
        }
    } catch (\TeamWorkPm\Exception $e) {
        print_r($e);
    }
}
