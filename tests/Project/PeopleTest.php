<?php

namespace TeamWorkPm\Tests\Project;

use TeamWorkPm\Tests\TestCase;

final class PeopleTest extends TestCase
{
    public function testAdd()
    {
        $this->assertTrue($this->factory('project.people', [
            'POST /projects/' . TPM_PROJECT_ID_1 . '/people/' . TPM_USER_ID => true
        ])->add(TPM_PROJECT_ID_1, TPM_USER_ID));
    }

    public function testGet()
    {
        $this->assertGreaterThan(0, count($this->factory('project.people', [
            'GET /projects/' . TPM_PROJECT_ID_1 . '/people/' . TPM_USER_ID => true
        ])->get(TPM_PROJECT_ID_1, TPM_USER_ID)));
    }

    /**
     * @dataProvider provider
     */
    public function testUpdate(array $data)
    {
        $data['project_id'] = TPM_PROJECT_ID_1;
        $data['person_id'] = TPM_USER_ID;

        $this->assertTrue($this->factory('project.people', [
            'PUT /projects/' . TPM_PROJECT_ID_1 . '/people/' . TPM_USER_ID => true
        ])->update($data));
    }

    public function testDelete()
    {
        $this->assertTrue($this->factory('project.people', [
            'DELETE /projects/' . TPM_PROJECT_ID_1 . '/people/' . TPM_USER_ID => true
        ])->delete(TPM_PROJECT_ID_1, TPM_USER_ID));
    }

    public function provider()
    {
        return [
            [
                [
                    'view_messages_and_files' => 0,
                    'view_tasks_and_milestones' => 1,
                    'view_time' => 1,
                    'view_notebooks' => 0,
                    'view_risk_register' => 0,
                    'view_invoices' => 0,
                    'view_links' => 0,
                    'add_tasks' => 0,
                    'add_milestones' => 0,
                    'add_taskLists' => 0,
                    'add_messages' => 0,
                    'add_files' => 0,
                    'add_time' => 0,
                    'add_links' => 0,
                ],
            ],
        ];
    }
}
