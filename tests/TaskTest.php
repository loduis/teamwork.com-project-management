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


        $this->assertEquals(TPM_TEST_ID, $this->factory('task', [
            'POST /projects/' . TPM_PROJECT_ID . '/tasks' => fn($data) => $this->assertMatchesJsonSnapshot($data)
        ])->create([
            'project_id' => TPM_PROJECT_ID,
            'content' => 'Test'
        ]));

        $this->assertEquals(TPM_TEST_ID, $this->factory('task', [
            'POST /tasklists/' . TPM_TASK_LIST_ID . '/tasks' => fn($data) => $this->assertMatchesJsonSnapshot($data)
        ])->create([
            'task_list_id' => TPM_TASK_LIST_ID,
            'content' => 'Test',
            'custom_fields' => [
                68 => 'Jane Doe',
                75 => 'Finance'
            ]
        ]));
    }

    /**
     * @dataProvider provider
     * @test
     */
    public function update($data): void
    {
        $this->assertTrue($this->factory('task', [
            'PUT /tasks/' . TPM_TASK_ID => fn($data) => $this->assertMatchesJsonSnapshot($data)
        ])->update([
            'id' => TPM_TASK_ID,
            'content' => 'Change content',
            'custom_fields' => []
        ]));
    }

    /**
     * @dataProvider provider
     * @test
     */
    public function add($data): void
    {
        $this->assertEquals(TPM_TEST_ID, $this->factory('task', [
            'POST /tasks/' . TPM_TASK_ID => fn($data) => $this->assertMatchesJsonSnapshot($data)
        ])->add(TPM_TASK_ID, [
            'content' => 'Sub task',
        ]));
    }

    /**
     * @test
     */
    public function delete(): void
    {
        $this->assertTrue($this->factory('task', [
            'DELETE /tasks/' . TPM_TASK_ID => true
        ])->delete(TPM_TASK_ID));
    }


    /**
     * @depends create
     * @test
     */
    public function getAll(): void
    {
        $this->assertGreaterThan(0,
            count($this->factory('task', [
                'GET /tasks' => true
            ])->all())
        );

    }
    /**
     * @depends create
     * @test
     */
    public function get(): void
    {
        $this->assertEquals(
            "Mi task with files",
            $this->factory('task', [
                'GET /tasks/' . TPM_TASK_ID => true
            ])->get(TPM_TASK_ID)->content
        );
    }

    /**
     * @depends create
     * @test
     */
    public function getByProject(): void
    {
        $this->assertGreaterThan(0,
            count($this->factory('task', [
                'GET /projects/' . TPM_PROJECT_ID . '/tasks' => true
            ])->getByProject(TPM_PROJECT_ID))
        );
    }

    /**
     * @depends create
     * @test
     */
    public function getByTaskList(): void
    {
        $this->assertGreaterThan(0,
            count($this->factory('task', [
                'GET /tasklists/' . TPM_TASK_LIST_ID . '/tasks' => true
            ])->getByTaskList(TPM_TASK_LIST_ID))
        );
    }

    /**
     * @depends create
     * @test
     */
    public function getCompleted(): void
    {
        $this->assertGreaterThan(0,
            count($this->factory('task', [
                'GET /completedtasks' => true
            ])->getCompleted())
        );
    }

    /**
     * @depends create
     * @test
     */
    public function getFollowers(): void
    {
        $this->assertGreaterThan(0,
            count($this->factory('task', [
                'GET /tasks/' . TPM_TASK_ID . '/followers' => true
            ])->getFollowers(TPM_TASK_ID)->changeFollowerIds)
        );
    }

    /**
     * @depends create
     * @test
     */
    public function getPredecessors(): void
    {
        $this->assertCount(0,
            $this->factory('task', [
                'GET /tasks/' . TPM_TASK_ID . '/predecessors' => true
            ])->getPredecessors(TPM_TASK_ID)
        );
    }

    /**
     * @depends create
     * @test
     */
    public function getSubTasks(): void
    {
        $this->assertCount(1,
            $this->factory('task', [
                'GET /tasks/' . TPM_TASK_ID . '/subtasks' => true
            ])->getSubTasks(TPM_TASK_ID)
        );
    }

    /**
     * @depends create
     * @test
     */
    public function getRecurring(): void
    {
        $task = $this->factory('task', [
            'GET /tasks/' . TPM_TASK_ID . '/recurring' => true
        ])->getRecurring(TPM_TASK_ID);

        $this->assertArrayHasKey('datesSet', $task);
        $this->assertArrayHasKey('recurringDates', $task);
    }

    /**
     * @test
     */
    public function complete(): void
    {
        $this->assertTrue($this->factory('task', [
            'PUT /tasks/' . TPM_TASK_ID . '/complete' => true
        ])->complete(TPM_TASK_ID));
    }

    /**
     * @test
     */
    public function unComplete(): void
    {
        $this->assertTrue($this->factory('task', [
            'PUT /tasks/' . TPM_TASK_ID . '/uncomplete' => true
        ])->unComplete(TPM_TASK_ID));
    }

    /**
     * @test
     */
    public function reorder(): void
    {
        $this->assertTrue($this->factory('task', [
            'PUT /tasklists/'. TPM_TASK_ID . '/tasks/reorder' => fn($data) => $this->assertMatchesJsonSnapshot($data)
        ])->reorder(TPM_TASK_ID, 1, 2, 2));
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
