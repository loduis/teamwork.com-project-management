<?php

namespace TeamWorkPm\Tests;

use TeamWorkPm\Exception;

final class TaskTest extends TestCase
{

    /**
     * @dataProvider provider
     * @test
     */
    public function create($data): void
    {
        try {
            $data['files'] = __DIR__ . '/uploads/teamworkpm.jpg';
            $this->factory('task')->create($data);
            $this->fail('An expected exception has not been raised.');
        } catch (Exception $e) {
            $this->assertEquals('Required field task_list_id or project_id', $e->getMessage());
        }

        /*
        try {
            $data['task_list_id'] = $this->taskListId;
            $id = $this->model->save($data);
            $this->assertGreaterThan(0, $id);
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
        */
    }

    /**
     * @dataProvider provider
     * @-test
     */
    public function update($data): void
    {
        try {
            $data['id'] = $this->id;
            $this->assertTrue($this->model->save($data));
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @-test
     */
    public function get(): void
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
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }

        try {
            $task = $this->model->get($this->id, true);
            $this->assertTrue(isset($task->timeIsLogged));
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @-test
     */
    public function getByTaskList(): void
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
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @-test
     */
    public function complete(): void
    {
        try {
            $times = $this->model->complete(0);
            $this->fail('An expected exception has not been raised.');
        } catch (Exception $e) {
            $this->assertEquals('Invalid param id', $e->getMessage());
        }

        try {
            $this->assertTrue($this->model->complete($this->id));
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @-test
     */
    public function getFinishedByTaskList(): void
    {
        try {
            $tasks = $this->model->getByTaskList($this->taskListId, 'finished');
            $this->assertGreaterThan(0, count($tasks));
            foreach ($tasks as $t) {
                $this->assertNotEmpty($t->completed);
            }
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @-test
     */
    public function unComplete(): void
    {
        try {
            $times = $this->model->uncomplete(0);
            $this->fail('An expected exception has not been raised.');
        } catch (Exception $e) {
            $this->assertEquals('Invalid param id', $e->getMessage());
        }

        try {
            $this->assertTrue($this->model->unComplete($this->id));
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @-test
     */
    public function getPendingByTaskList(): void
    {
        try {
            $tasks = $this->model->getByTaskList($this->taskListId, 'pending');
            $this->assertGreaterThan(0, count($tasks));
            foreach ($tasks as $t) {
                $this->assertEmpty($t->completed);
            }
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @dataProvider provider
     * @-test
     */
    public function reorder($data): void
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
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    public function provider()
    {
        return [
            [
                [
                    'content' => 'Test Task',
                    'notify' => false,
                    'description' => 'Bla, Bla, Bla',
                    'due_date' => date('Ymd', strtotime('+10 days')),
                    'start_date' => date('Ymd'),
                    'private' => false,
                    'priority' => 'high',
                    'estimated_minutes' => 1000,
                    'responsible_party_id' => null,
                    'attachments' => null,
                    'pending_file_attachments' => null,
                ],
            ],
        ];
    }
}
