<?php

namespace TeamWorkPm\Tests\Task;

use TeamWorkPm\Exception;
use TeamWorkPm\Tests\TestCase;

final class ListTest extends TestCase
{
    /**
     * @test
     */
    public function insert(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Required field name');
        $this->factory('task.list')->save([
            'project_id' => TPM_PROJECT_ID,
        ]);
    }

    /**
     * @test
     */
    public function insertReal(): void
    {
        $this->assertEquals(TPM_TEST_ID, $this->factory('task.list', [
            'POST /projects/'. TPM_PROJECT_ID . '/tasklists' => fn($data) => $this->assertMatchesJsonSnapshot($data)
        ])->create([
            'project_id' => TPM_PROJECT_ID,
            'name' => 'Test'
        ]));

        $this->assertEquals(TPM_TEST_ID, $this->factory('task.list', [
            'POST /projects/'. TPM_PROJECT_ID . '/tasklists' => fn($data) => $this->assertMatchesJsonSnapshot($data)
        ])->create([
            'project_id' => TPM_PROJECT_ID,
            'apply_defaults_to_existing_tasks' => true,
            'name' => 'Test'
        ]));
    }

    /**
     * @test
     */
    public function update(): void
    {
        $this->assertTrue($this->factory('task.list', [
            'PUT /tasklists/' . TPM_TASK_LIST_ID => fn($data) => $this->assertMatchesJsonSnapshot($data)
        ])->update([
            'id' => TPM_TASK_LIST_ID,
            'content' => 'Change content',
            'apply_defaults_to_existing_tasks' => true
        ]));
    }

    /**
     * @depends insert
     * @test
     */
    public function getAll(): void
    {
        $this->assertGreaterThan(0,
            count($this->factory('task.list', [
                'GET /tasklists' => true
            ])->all())
        );

    }
    /**
     * @depends insert
     * @test
     */
    public function get(): void
    {
        $this->assertEquals(
            "My List",
            $this->factory('task.list', [
                'GET /tasklists/' . TPM_TASK_LIST_ID => true
            ])->get(TPM_TASK_LIST_ID)->name
        );
    }

    /**
     * @depends insert
     * @test
     */
    public function getByProject(): void
    {
        $this->assertGreaterThan(0,
            count($this->factory('task.list', [
                'GET /projects/' . TPM_PROJECT_ID . '/tasklists' => true
            ])->getByProject(TPM_PROJECT_ID))
        );
    }

    /**
     * @depends insert
     * @test
     */
    public function getTemplates(): void
    {
        $this->assertGreaterThan(0,
            count($this->factory('task.list', [
                'GET /tasklists/templates' => true
            ])->getTemplates())
        );
    }

    /**
     * @test
     */
    public function reorder(): void
    {
        $this->assertTrue($this->factory('task.list', [
            'PUT /projects/'. TPM_PROJECT_ID . '/tasklists/reorder' => fn($data) => $this->assertMatchesJsonSnapshot($data)
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
