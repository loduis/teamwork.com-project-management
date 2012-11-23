<?php

class TestCase extends PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        TeamWorkPm::setAuth(API_COMPANY, API_KEY);
        TeamWorkPm::setFormat(API_FORMAT);
    }

    protected function getFirstProject()
    {
        static $id = null;
        if ($id === null) {
            $project = TeamWorkPm::factory('project');
            foreach($project->getAll() as $p) {
                $id = $p->id;
                break;
            }
            if ($id === null) {
                $project = TeamWorkPm::factory('project');
                $id = $project->insert($data);
            }
        }
        return (int) $id;
    }
    /*
    public static function tearDownAfterClass()
    {
        $className = get_called_class();
        if ($className !== 'ProjectTest') {
            $id = self::getFirstProject();
            if ($id !== null) {
                $project = TeamWorkPm::factory('project');
                $project->delete($id);
            }
        }
    }*/
}