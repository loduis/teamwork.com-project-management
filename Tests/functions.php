<?php

function rand_string($string, $length = 10)
{
    $source  = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    return $string . ' - ' . substr(str_shuffle($source), 0, $length);
}


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

function get_first_link_category_id($project_id)
{
    static $id = null;
    if ($id === null) {
        $category = TeamWorkPm::factory('category/link');
        foreach($category->getByProject($project_id) as $c) {
            $id = $c->id;
            break;
        }
    }
    return (int) $id;
}

function get_first_file_category_id($project_id)
{
    static $id = null;
    if ($id === null) {
        $category = TeamWorkPm::factory('category/file');
        foreach($category->getByProject($project_id) as $c) {
            $id = $c->id;
            break;
        }
    }
    return (int) $id;
}

function get_first_notebook_category_id($project_id)
{
    static $id = null;
    if ($id === null) {
        $category = TeamWorkPm::factory('category/notebook');
        foreach($category->getByProject($project_id) as $c) {
            $id = $c->id;
            break;
        }
    }
    return (int) $id;
}

function get_first_message_category_id($project_id)
{
    static $id = null;
    if ($id === null) {
        $category = TeamWorkPm::factory('category/message');
        foreach($category->getByProject($project_id) as $c) {
            $id = $c->id;
            break;
        }
    }
    return (int) $id;
}



function get_first_person_id($project_id = null)
{
    static $id = null;
    if ($id === null) {
        $people = TeamWorkPm::factory('people');
        $method = $project_id ? 'getByProject' : 'getAll';
        foreach($people->$method($project_id) as $p) {
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

function get_first_message_id($project_id)
{
    static $id = null;
    if ($id === null) {
        $message = TeamWorkPm::factory('message');
        foreach($message->getByProject($project_id) as $m) {
            $id = $m->id;
            break;
        }
    }
    return (int) $id;
}



function get_first_file_id($project_id)
{
    static $id = null;
    if ($id === null) {
        $file = TeamWorkPm::factory('file');
        foreach($file->getByProject($project_id) as $f) {
            $id = $f->id;
            break;
        }
    }
    return (int) $id;
}

/*
function get_first_file_version_id($project_id)
{
    static $id = null;
    if ($id === null) {
        $milestone = TeamWorkPm::factory('file');
        foreach($milestone->getByProject($project_id) as $f) {
            $id = $f->versionId;
            break;
        }
    }
    return (int) $id;
}*/


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

function get_first_link_id()
{
    static $id = null;
    if ($id === null) {
        $link = TeamWorkPm::factory('link');
        $links = $link->getAll();
        foreach($links as $l) {
            $id = $l->id;
            break;
        }
    }
    return (int) $id;
}

function get_first_company_id()
{
    static $id = null;
    if ($id === null) {
        $company = TeamWorkPm::factory('company');
        $companies = $company->getAll();
        foreach($companies as $c) {
            $id = $c->id;
            break;
        }
    }
    return (int) $id;
}

function get_first_milestone_comment_id($milestone_id)
{
    static $id = null;
    if ($id === null) {
        $comment      = TeamWorkPm::factory('comment/milestone');
        foreach ($comment->getRecent($milestone_id) as $c) {
            $id = $c->id;
            break;
        }
    }
    return (int) $id;
}

function get_first_task_comment_id($task_id)
{
    static $id = null;
    if ($id === null) {
        $comment      = TeamWorkPm::factory('comment/task');
        foreach ($comment->getRecent($task_id) as $c) {
            $id = $c->id;
            break;
        }
    }
    return (int) $id;
}

function get_first_file_comment_id($file_id)
{
    static $id = null;
    if ($id === null) {
        $comment      = TeamWorkPm::factory('comment/file');
        foreach ($comment->getRecent($file_id) as $c) {
            $id = $c->id;
            break;
        }
    }
    return (int) $id;
}



function get_first_notebook_comment_id($notebook_id)
{
    static $id = null;
    if ($id === null) {
        $comment      = TeamWorkPm::factory('comment/notebook');
        foreach ($comment->getRecent($notebook_id) as $c) {
            $id = $c->id;
            break;
        }
    }
    return (int) $id;
}

function get_first_notebook_id($project_id)
{
    static $id = null;
    if ($id === null) {
        $notebook = TeamWorkPm::factory('notebook');
        foreach($notebook->getByProject($project_id) as $n) {
            $id = $n->id;
            break;
        }
    }
    return (int) $id;
}
