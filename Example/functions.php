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

function test_boostrap($callback = 'null_return') {
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

function get_first_todo_list()
{
    $project_id = get_first_project();
    $list = TeamWorkPm::factory('Todo/List');
    $lists = $list->getActiveByProject($project_id);
    foreach ($lists as $l) {
        return $l->id;
    }
}

function get_first_todo_item() {
    $todo_list_id =  get_first_todo_list();
    $item = TeamWorkPm::factory('Todo/Item');
    $items = $item->getPendingByTodoList($todo_list_id);
    foreach ($items as $i) {
        return $i->id;
    }
}

function get_first_todo_item_finished() {
    $todo_list_id =  get_first_todo_list();
    $item = TeamWorkPm::factory('Todo/Item');
    $items = $item->getFinishedByTodoList($todo_list_id);
    foreach ($items as $i) {
        return (int) $i->id;
    }
}

function get_first_completed_todo_list()
{
    $project_id = get_first_project();
    $list = TeamWorkPm::factory('Todo/List');
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

function get_first_user() {
    return '5625';
}