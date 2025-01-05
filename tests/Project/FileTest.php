<?php

namespace TeamWorkPm\Tests\Project;

use TeamWorkPm\Tests\TestCase;

final class FileTest extends TestCase
{
    public function testAll(): void
    {
        $this->assertGreaterThan(0, count($this->factory('project.file', [
            'GET /projects/' . TPM_PROJECT_ID_1 . '/files' => true
        ])->all(TPM_PROJECT_ID_1)));
    }

    public function testAdd(): void
    {
        $this->assertGreaterThan(0, $this->factory('project.file', [
            'POST /projects/' . TPM_PROJECT_ID_1 . '/files' => fn($data) => $this->assertMatchesJsonSnapshot($data)
        ])->add(TPM_PROJECT_ID_1, [
            'pending_file_ref' => 'tf_3d9cfae3-65f7-4ff8-8bf5-ca0512de600a'
        ]));
    }
}