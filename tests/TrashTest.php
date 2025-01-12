<?php

namespace TeamWorkPm\Tests;

final class TrashTest extends TestCase
{
    public function testAll(): void
    {
        $this->assertGreaterThan(0, count($this->factory('trash', [
           'GET /trashcan/projects/' . TPM_PROJECT_ID_1 => true
        ])->all(TPM_PROJECT_ID_1)->tasks));
    }

    public function testRestore(): void
    {
        $this->assertTrue($this->factory('trash', [
            'PUT /trashcan/tasks/' . TPM_TASK_ID . '/restore' => true
        ])->restore('tasks', TPM_TASK_ID));
    }
}