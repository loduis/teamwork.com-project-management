<?php

class FinishedTest extends TestCase
{
    private $projectId;

    public function setUp()
    {
        parent::setUp();

        $this->projectId = get_first_project_id();
    }


    /**
     *
     * @test
     */

    public function getTaskListWithoutTasks()
    {
        try {
            $model = TeamWorkPm::factory('task/list');
            $project_id = get_first_project_id();
            $id        = get_first_task_list_id($project_id);
            $list = $model->get($id, false);
            $this->assertCount(0, get_object_vars($list->todoItems));
        } catch (\TeamWorkPm\Exception $e) {
            $this->assertTrue(false, $e->getMessage());
        }
    }

    /**
     *
     * @test
     */
    public function deleteCategoryProject()
    {
        try {
            $id = get_first_project_category_id();
            $category = TeamWorkPm::factory('category/project');
            $this->assertTrue($category->delete($id));
        } catch (\TeamWorkPm\Exception $e) {
            $this->assertTrue(false, $e->getMessage());
        }
    }

    /**
     *
     * @test
     */
    public function deleteCategoryLink()
    {
        try {
            $category = TeamWorkPm::factory('category/link');
            foreach($category->getByProject($this->projectId) as $c) {
                $this->assertTrue($category->delete($c->id));
            }
        } catch (\TeamWorkPm\Exception $e) {
            $this->assertTrue(false, $e->getMessage());
        }
    }

    /**
     * @test
     */
    public function deleteTime()
    {
        $this->fail('Delete time api bug.');
        try {
            $time = TeamWorkPm::factory('time');
            $times = $time->getAll();
            foreach($times as $t) {
                $this->assertTrue($time->delete($t->id));
            }
        } catch (Exception $e) {
            $this->assertTrue(false, $e->getMessage());
        }
    }

    /**
     * @test
     */
    public function deleteCompany()
    {
        try {
            $company = TeamWorkPm::factory('company');
            $companies = $company->getAll();
            foreach($companies as $c) {
                $this->assertTrue($company->delete($c->id));
            }
        } catch (\TeamWorkPm\Exception $e) {
            $code = $e->getCode();
            switch ($code) {
              case \TeamWorkPm\Error::CAN_NOT_DELETE_THIS_RESOURCE:
                $this->markTestSkipped($e->getMessage());
                break;
              default:
                $this->assertTrue(false, $e->getMessage());
                break;
            }
        }
    }

    /**
     *
     * @test
     */
    public function deleteMilestone()
    {
        try {
            $id = get_first_milestone_id($this->projectId);
            $milestone = TeamWorkPm::factory('milestone');
            $this->assertTrue($milestone->delete($id));
        } catch (\TeamWorkPm\Exception $e) {
            $this->assertTrue(false, $e->getMessage());
        }
    }

    /**
     *
     * @test
     */
    public function deleteTask()
    {
        try {
            $project_id   = get_first_project_id();
            $task_list_id = get_first_task_list_id($project_id);
            $id           = get_first_task_id($task_list_id);
            $task         = TeamWorkPm::factory('task');
            $this->assertTrue($task->delete($id));
        } catch (\TeamWorkPm\Exception $e) {
            $this->assertTrue(false, $e->getMessage());
        }
    }


    /**
     *
     * @test
     */
    public function deleteTaskList()
    {
        try {
            $id = get_first_task_list_id($this->projectId);
            $list = TeamWorkPm::factory('task/list');
            $this->assertTrue($list->delete($id));
        } catch (\TeamWorkPm\Exception $e) {
            $this->assertTrue(false, $e->getMessage());
        }
    }

    /**
     * @test
     */
    public function deleteLink()
    {
        try {
            $link = TeamWorkPm::factory('link');
            $links = $link->getAll();
            foreach($links as $l) {
                $this->assertTrue($link->delete($l->id));
            }
        } catch (Exception $e) {
            $this->assertTrue(false, $e->getMessage());
        }
    }

    /**
     * @test
     */
    public function deleteProject()
    {
        try {
            $project = TeamWorkPm::factory('project');
            $this->assertTrue($project->delete($this->projectId));
        } catch (\TeamWorkPm\Exception $e) {
            $this->assertTrue(false, $e->getMessage());
        }
    }
}