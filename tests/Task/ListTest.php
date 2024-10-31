<?php

namespace TeamWorkPm\Tests\Task;

use TeamWorkPm\Exception;
use TeamWorkPm\Tests\TestCase;

final class ListTest extends TestCase
{
    /**
     * @dataProvider provider
     * @_test
     */
    public function insert($data): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Required field name');
        $this->assertEquals(10, $this->factory('task.list')->save([
            'project_id' => TPM_PROJECT_ID,
        ]));
    }

    /**
     * @_test
     */
    public function insertReal(): void
    {
        $this->assertEquals(10, $this->factory('task.list', [
            'POST /projects/'. TPM_PROJECT_ID . '/tasklists' => function ($data) {
                $this->assertObjectHasProperty('name', $data);
            }
        ])->save([
            'project_id' => TPM_PROJECT_ID,
            'name' => 'Test'
        ]));

        $this->assertEquals(10, $this->factory('task.list', [
            'POST /projects/'. TPM_PROJECT_ID . '/tasklists' => function ($data) {
                $this->assertObjectHasProperty('todo-list', $data);
                $this->assertObjectHasProperty('applyDefaultsToExistingTasks', $data);
            }
        ])->save([
            'project_id' => TPM_PROJECT_ID,
            'apply_defaults_to_existing_tasks' => true,
            'name' => 'Test'
        ]));
    }

    /**
     * @test
     */
    public function reorder(): void
    {
        $this->assertTrue($this->factory('task.list', [
            'PUT /projects/'. TPM_PROJECT_ID . '/tasklists/reorder' => function ($data) {
                $this->assertEquals(
                    '{"todo-lists":{"todo-list":[{"id":1},{"id":2},{"id":2}]}}',
                    $data
                );
            }
        ])->reorder(TPM_PROJECT_ID, 1, 2, 2));
    }

    public function provider()
    {
        return [
            [
                [
                    'name' => 'Test Task List',
                    'description' => 'Bla, Bla, Bla',
                    'private' => false,
                    'pinned' => false,
                    'tracked' => false,
                ],
            ],
        ];
    }
}
