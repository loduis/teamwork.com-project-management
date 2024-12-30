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
            'project_id' => TPM_PROJECT_ID_1,
        ]);
    }

    /**
     * @test
     */
    public function insertReal(): void
    {
        $this->assertEquals(TPM_TEST_ID, $this->factory('task.list', [
            'POST /projects/'. TPM_PROJECT_ID_1 . '/tasklists' => fn($data) => $this->assertMatchesJsonSnapshot($data)
        ])->create([
            'project_id' => TPM_PROJECT_ID_1,
            'name' => 'Test'
        ]));

        $this->assertEquals(TPM_TEST_ID, $this->factory('task.list', [
            'POST /projects/'. TPM_PROJECT_ID_1 . '/tasklists' => fn($data) => $this->assertMatchesJsonSnapshot($data)
        ])->create([
            'project_id' => TPM_PROJECT_ID_1,
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
                'GET /projects/' . TPM_PROJECT_ID_1 . '/tasklists' => true
            ])->getByProject(TPM_PROJECT_ID_1))
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

    public function testGetTotalTime(): void
   {
        $this->assertEquals(15, $this->factory('task.list', [
            'GET /tasklists/' . TPM_TASK_LIST_ID .'/time/total' => true
        ])->getTotalTime(TPM_TASK_LIST_ID)->timeTotals->filteredEstimatedMinsSum);
    }

    /**
     * @test
     */
    public function reorder(): void
    {
        $this->assertTrue($this->factory('task.list', [
            'PUT /projects/'. TPM_PROJECT_ID_1 . '/tasklists/reorder' => fn($data) => $this->assertMatchesJsonSnapshot($data)
        ])->reorder(TPM_PROJECT_ID_1, 1, 2, 2));
    }

    /**
     * @test
     */
    public function delete(): void
    {
        $this->assertTrue($this->factory('task.list', [
            'DELETE /tasklists/' . TPM_TASK_LIST_ID => true
        ])->delete(TPM_TASK_LIST_ID));
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
