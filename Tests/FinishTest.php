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
     * @test
     */
    public function deleteTime()
    {
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