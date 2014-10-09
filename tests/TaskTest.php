<?php

class TaskTest extends TestCase
{
    private $model;
    private $taskListId;
    private $id;

    public function setUp()
    {
        parent::setUp();
        $this->model      = TeamWorkPm\Factory::build('task');
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
            $data['files'] = __DIR__ . '/uploads/teamworkpm.jpg';
            $this->model->save($data);
            $this->fail('An expected exception has not been raised.');
        } catch (Exception $e) {
            $this->assertEquals('Required field task_list_id', $e->getMessage());
        }
        try {
            $data['task_list_id'] = $this->taskListId;
            $id                   = $this->model->save($data);
            $this->assertGreaterThan(0, $id);
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @dataProvider provider
     * @test
     */
    public function update($data)
    {
        try {
            $data['id'] = $this->id;
            $this->assertTrue($this->model->save($data));
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     *
     * @test
     */
    public function get()
    {
        try {
            $times = $this->model->get(0);
            $this->fail('An expected exception has not been raised.');
        } catch (Exception $e) {
            $this->assertEquals('Invalid param id', $e->getMessage());
        }
        try {
            $task = $this->model->get($this->id);
            $this->assertEquals($this->id, $task->id);
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
        try {
            $task = $this->model->get($this->id, true);
            $this->assertTrue(isset($task->timeIsLogged));
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     *
     * @test
     */
    public function getByTaskList()
    {
        try {
            $times = $this->model->getByTaskList(0);
            $this->fail('An expected exception has not been raised.');
        } catch (Exception $e) {
            $this->assertEquals('Invalid param task_list_id', $e->getMessage());
        }
        try {
            $tasks = $this->model->getByTaskList($this->taskListId);
            $this->assertGreaterThan(0, count($tasks));
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @test
     */
    public function complete()
    {
        try {
            $times = $this->model->complete(0);
            $this->fail('An expected exception has not been raised.');
        } catch (Exception $e) {
            $this->assertEquals('Invalid param id', $e->getMessage());
        }
        try {
            $this->assertTrue($this->model->complete($this->id));
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
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
            $this->fail($e->getMessage());
        }
    }

    /**
     * @test
     */
    public function unComplete()
    {
        try {
            $times = $this->model->uncomplete(0);
            $this->fail('An expected exception has not been raised.');
        } catch (Exception $e) {
            $this->assertEquals('Invalid param id', $e->getMessage());
        }
        try {
            $this->assertTrue($this->model->unComplete($this->id));
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
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
            $this->fail($e->getMessage());
        }
    }

    /**
     * @dataProvider provider
     * @test
     */
    public function reorder($data)
    {
        try {
            $this->model->reorder(0, []);
            $this->fail('An expected exception has not been raised.');
        } catch (Exception $e) {
            $this->assertEquals('Invalid param task_list_id', $e->getMessage());
        }
        try {
            $data['task_list_id'] = $this->taskListId;
            $id = $this->model->save($data);
            $this->assertGreaterThan(0, $id);
            $tasks = $this->model->getByTaskList($this->taskListId);
            $ids = [];
            foreach ($tasks as $t) {
                $ids[] = $t->id;
            }
            shuffle($ids);
            $this->assertTrue($this->model->reorder($this->taskListId, $ids));
            $tasks = $this->model->getByTaskList($this->taskListId);
            $order = [];
            foreach ($tasks as $t) {
                $order[] = $t->id;
            }
            $this->assertEquals($ids, $order);
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    public function provider()
    {
        return [
            [
              [
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
              ]
            ]
        ];
    }
}