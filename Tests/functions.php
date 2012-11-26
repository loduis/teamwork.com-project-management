<?php

function get_first_project_id()
{
    static $id = null;
    if ($id === null) {
        $project = TeamWorkPm::factory('project');
        foreach($project->getAll() as $p) {
            $id = $p->id;
            break;
        }
    }
    return (int) $id;
}

function get_first_project_category_id()
{
    static $id = null;
    if ($id === null) {
        $category = TeamWorkPm::factory('category/project');
        foreach($category->getAll() as $c) {
            $id = $c->id;
            break;
        }
    }
    return (int) $id;
}

function get_first_people_id($project_id)
{
    static $id = null;
    if ($id === null) {
        $people = TeamWorkPm::factory('people');
        foreach($people->getByProject($project_id) as $p) {
            $id = $p->id;
            break;
        }
    }
    return (int) $id;
}

function get_first_milestone_id($project_id)
{
    static $id = null;
    if ($id === null) {
        $milestone = TeamWorkPm::factory('milestone');
        foreach($milestone->getByProject($project_id) as $m) {
            $id = $m->id;
            break;
        }
    }
    return (int) $id;
}

function get_first_task_list_id($project_id)
{
    static $id = null;
    if ($id === null) {
        $list = TeamWorkPm::factory('task/list');
        foreach($list->getByProject($project_id) as $t) {
            $id = $t->id;
            break;
        }
    }
    return (int) $id;
}

function get_first_task_id($task_list_id)
{
    static $id = null;
    if ($id === null) {
        $task = TeamWorkPm::factory('task');
        foreach($task->getByTaskList($task_list_id) as $t) {
            $id = $t->id;
            break;
        }
    }
    return (int) $id;
}

function get_first_time_id($task_id)
{
    static $id = null;
    if ($id === null) {
        $time = TeamWorkPm::factory('time');
        foreach($time->getByTask($task_id) as $t) {
            $id = $t->id;
            break;
        }
    }
    return (int) $id;
}