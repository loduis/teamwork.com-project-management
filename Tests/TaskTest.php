<?php

class TaskTest extends TestCase
{
    private $model;
    private $taskListId;
    private $id;

    public function setUp()
    {
        parent::setUp();
        $this->model      = TeamWorkPm::factory('task');
        $projectId        = get_first_project_id();
        $this->taskListId = get_first_task_list_id($projectId);
        $this->id         = get_first_task_id($this->taskListId);

    }

    /**
     * @dataProvider provider
     * @test
     */
    public function insert($data)
    {
        try {
            $data['task_list_id'] = $this->taskListId;
            $id                   = $this->model->save($data);
            $this->assertGreaterThan(0, $id);
        } catch (\TeamWorkPm\Exception $e) {
            $this->assertTrue(false, $e->getMessage());
        }
    }

    /**
     * @expectedException        \TeamWorkPm\Exception
     * @expectedExceptionMessage Required field task_list_id
     * @dataProvider provider
     * @test
     */
    public function insertWithoutTaskListId($data)
    {
        $this->model->save($data);
    }

    /**
     * @expectedException        \TeamWorkPm\Exception
     * @expectedExceptionMessage Invalid param id
     * @test
     */
    public function getWithInvalidId()
    {
        $this->model->get(0);
    }

    /**
     *
     * @test
     */
    public function get()
    {
        try {
            $task = $this->model->get($this->id);
            $this->assertEquals($this->id, $task->id);
        } catch (\TeamWorkPm\Exception $e) {
            $this->assertTrue(false, $e->getMessage());
        }
    }

    /**
     *
     * @test
     */
    public function getWithLoggedTime()
    {
        try {
            $task = $this->model->get($this->id, true);
            $this->assertTrue(isset($task->loggedTime));
        } catch (\TeamWorkPm\Exception $e) {
            $this->assertTrue(false, $e->getMessage());
        }
    }

    /**
     * @expectedException        \TeamWorkPm\Exception
     * @expectedExceptionMessage Invalid param task_list_id
     * @test
     */
    public function getByTaskListWithInvalidId()
    {
        $this->model->getByTaskList(0);
    }

    /**
     *
     * @test
     */
    public function getByTaskList()
    {
        try {
            $tasks = $this->model->getByTaskList($this->taskListId);
            $this->assertGreaterThan(0, count($tasks));
        } catch (\TeamWorkPm\Exception $e) {
            $this->assertTrue(false, $e->getMessage());
        }
    }

    /**
     * @expectedException        \TeamWorkPm\Exception
     * @expectedExceptionMessage Invalid param id
     * @test
     */
    public function completeWithInvalidId()
    {
        $this->model->complete(0);
    }

    /**
     * @test
     */
    public function complete()
    {
        try {
            $this->assertTrue($this->model->complete($this->id));
        } catch (\TeamWorkPm\Exception $e) {
            $this->assertTrue(false, $e->getMessage());
        }
    }

    /**
     *
     * @test
     */
    public function getFinishedByTaskList()
    {
        try {
            $tasks = $this->model->getByTaskList($this->taskListId, 'finished');
            $this->assertGreaterThan(0, count($tasks));
            foreach ($tasks as $t) {
                $this->assertNotEmpty($t->completed);
            }
        } catch (\TeamWorkPm\Exception $e) {
            $this->assertTrue(false, $e->getMessage());
        }
    }

    /**
     * @expectedException        \TeamWorkPm\Exception
     * @expectedExceptionMessage Invalid param id
     * @test
     */
    public function unCompleteWithInvalidId()
    {
        $this->model->unComplete(0);
    }

    /**
     * @test
     */
    public function unComplete()
    {
        try {
            $this->assertTrue($this->model->unComplete($this->id));
        } catch (\TeamWorkPm\Exception $e) {
            $this->assertTrue(false, $e->getMessage());
        }
    }

    /**
     *
     * @test
     */
    public function getPendingByTaskList()
    {
        try {
            $tasks = $this->model->getByTaskList($this->taskListId, 'pending');
            $this->assertGreaterThan(0, count($tasks));
            foreach ($tasks as $t) {
                $this->assertEmpty($t->completed);
            }
        } catch (\TeamWorkPm\Exception $e) {
            $this->assertTrue(false, $e->getMessage());
        }
    }

    /**
     * @expectedException        \TeamWorkPm\Exception
     * @expectedExceptionMessage Invalid param task_list_id
     * @test
     */
    public function reorderWithInvalidId()
    {
        $this->model->reorder(0, array());
    }

    /**
     * @dataProvider provider
     * @test
     */
    public function reorder($data)
    {
        try {
            $data['task_list_id'] = $this->taskListId;
            $id = $this->model->save($data);
            $this->assertGreaterThan(0, $id);
            $tasks = $this->model->getByTaskList($this->taskListId);
            $ids = array();
            foreach ($tasks as $t) {
                $ids[] = $t->id;
            }
            shuffle($ids);
            $this->assertTrue($this->model->reorder($this->taskListId, $ids));
            $tasks = $this->model->getByTaskList($this->taskListId);
            $order = array();
            foreach ($tasks as $t) {
                $order[] = $t->id;
            }
            $this->assertEquals($ids, $order);
        } catch (\TeamWorkPm\Exception $e) {
            $this->assertTrue(false, $e->getMessage());
        }
    }

    public function provider()
    {
        return array(
            array(
              array(
                'content'     => 'Test Task',
                'notify'      => false,
                'description' => 'Bla, Bla, Bla',
                'due_date'    => date('Ymd', strtotime('+10 days')),
                'start_date'  => date('Ymd'),
                'private'     => false,
                'priority'    => 'high',
                'estimated_minutes' => 1000,
                'responsible_party_id' => null,
                'attachments'          => null,
                'pending_file_attachments'=> null
              )
            )
        );
    }
}