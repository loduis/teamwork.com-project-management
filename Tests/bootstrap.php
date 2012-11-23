<?php

require __DIR__ . '/TestCase.php';

function load_data($name)
{
    $filename = __DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR .
                $name . '.json';
    $data = array();
    if (file_exists($filename)) {
        $content = file_get_contents($filename);
        $data    = json_decode($content, true);
    }
    return $data;
}

spl_autoload_register(function ($className) {
  $basedir = dirname(__DIR__);
  $filename = $basedir . DIRECTORY_SEPARATOR . $className . '.php';
  if (file_exists($filename)) {
    require $filename;
  }
});

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
        foreach($milestone->getAll($project_id) as $m) {
            $id = $m->id;
            break;
        }
    }
    return (int) $id;
}