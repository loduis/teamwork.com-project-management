<?php

namespace TeamWorkPm\Tests;

final class TemplateTest extends TestCase
{
    /**
    * @dataProvider provider
    */
    public function testCreate(array $data): void
    {
      $this->assertEquals(TPM_TEST_ID, $this->factory('project.template', [
           'POST /projects/template' => fn($data) => $this->assertMatchesJsonSnapshot($data)
        ])->create($data));
    }

    public function testAll(): void
    {
        $this->assertGreaterThan(0, count($this->factory('project.template', [
           'GET /projects/api/v3/projects/templates' => true
        ])->all()));
    }

    /**
    * @dataProvider provider
    */
    public function testUpdate(array $data): void
    {
        $data['id'] = TPM_PROJECT_TEMPLATE_ID;
        $data['name'] = 'Updated Template Name';
        $this->assertTrue($this->factory('project.template', [
            'PUT /projects/' . TPM_PROJECT_TEMPLATE_ID => true
        ])->update($data));
    }

    public function testDelete(): void
    {
        $this->assertTrue($this->factory('project.template', [
            'DELETE /projects/' . TPM_PROJECT_TEMPLATE_ID => true
        ])->delete(TPM_PROJECT_TEMPLATE_ID));
    }

    public function provider(): array
    {
        return [
            [
                [
                    'name' => 'Test Project',
                    'description' => 'Description of Test Project',
                    'use_tasks' => true
                ],
            ],
        ];
    }
}