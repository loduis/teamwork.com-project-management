<?php

namespace TeamWorkPm\Tests\Request;

use TeamWorkPm\Exception;
use TeamWorkPm\Tests\TestCase;
use TeamWorkPm\Rest\Request\JSON as Request;

const SCHEMA_PATH = __DIR__ . '/../../src/Rest/Resource/schemas/';

class JsonTest extends TestCase
{

    public function testProjectSchema()
    {
        $fields = json_decode(file_get_contents(SCHEMA_PATH . 'projects.json'), true);
        $request = new Request();
        $request->setParent('project');
        $request->setFields($fields);

        $res = $request->getParameters('POST', [
            'name' => 'Test',
            'custom_fields' => [
                '123' => 'valor1',
                '456' => 'valor2'
            ]
        ]);

        $this->assertMatchesJsonSnapshot($res);

        $res = $request->getParameters('POST', [
            'name' => 'Test',
            'custom_fields' => [
                [
                    'custom_field_id' => 123,
                    'value' => 'valor1'
                ],
                [
                    'custom_field_id' => 456,
                    'value' => 'valor2'
                ],
            ]
        ]);

        $this->assertMatchesJsonSnapshot($res);
    }

    public function testPeopleSchema()
    {
        $fields = json_decode(file_get_contents(SCHEMA_PATH . 'people.json'), true);
        $request = new Request();
        $request->setParent('person');
        $request->setFields($fields);


        $res = $request->getParameters('POST', [
            'first_name' => 'Developer',
            'last_name' => 'Teamwork',
            'email_address' => 'tes@gmail.com',
            'working_hours' => [
                [
                    "weekday" => "Monday",
                    "task_hours" => 8
                ],
                [
                    "weekday" => "Tuesday",
                    "task_hours" => 6
                ]
            ]
        ]);

        $this->assertMatchesJsonSnapshot($res);

        $res = $request->getParameters('POST', [
            'first_name' => 'Developer',
            'last_name' => 'Teamwork',
            'email_address' => 'tes@gmail.com',
            'working_hours' => (object) [
                (object) [
                    "weekday" => "Monday",
                    "task_hours" => 8
                ],
                (object) [
                    "weekday" => "Tuesday",
                    "task_hours" => 6
                ]
            ]
        ]);

        $this->assertMatchesJsonSnapshot($res);

        $res = $request->getParameters('POST', [
            'first_name' => 'Developer',
            'last_name' => 'Teamwork',
            'email_address' => 'tes@gmail.com',
        ]);

        $this->assertMatchesJsonSnapshot($res);
    }

    public function testTaskListSchema()
    {
        $fields = json_decode(file_get_contents(SCHEMA_PATH . 'tasklists.json'), true);
        $request = new Request();

        $request->setParent('todo-list');
        $request->setFields($fields);

        $res = $request->getParameters('POST', [
            'apply_defaults_to_existing_tasks' => true,
            'todo_list' => [
                'name' => 'Test'
            ]
        ]);

        $this->assertMatchesJsonSnapshot($res);
    }

    public function testProjectRate()
    {
        $fields = json_decode(file_get_contents(SCHEMA_PATH . 'projects/rates.json'), true);
        $request = new Request();
        $request->setParent('rates');
        $request->setFields($fields);

        $res = $request->getParameters('POST', [
            'project_default' => 1,
            'users' => [
                TPM_USER_ID => 5
            ]
        ]);

        $this->assertMatchesJsonSnapshot($res);

        $res = $request->getParameters('POST', [
            'project_default' => 1,
            'users' => [
                TPM_USER_ID => [
                    'rate' => 5,
                    'test' => 1 // this is not defined field.
                ]
            ]
        ]);

        $this->assertMatchesJsonSnapshot($res);
    }

    public function testCustomFields()
    {
        $fields = json_decode(file_get_contents(SCHEMA_PATH . 'projects/custom_fields.json'), true);
        $request = new Request();
        $request->setParent('customfield');
        $request->setFields($fields);

        $res = $request->getParameters('POST', [
            'name' => 'Valid Custom Field',
            'entity' => 'project',
            'type' => 'text-short',
            'description' => 'A valid custom field for testing',
            'options' => [
                ['color' => 'red', 'value' => 'High'],
                ['color' => 'green', 'value' => 'Low']
            ],
        ]);

        $this->assertMatchesJsonSnapshot($res);

        $res = $request->getParameters('POST', [
            'name' => 'Valid Custom Field',
            'entity' => 'project',
            'type' => 'text-short',
            'description' => 'A valid custom field for testing',
            'options' => [
                'choices' => [
                    ['color' => 'red', 'value' => 'High'],
                    ['color' => 'green', 'value' => 'Low']
                ]
            ],
        ]);

        $this->assertMatchesJsonSnapshot($res);
    }

    public function testProjectFiles()
    {
        $fields = json_decode(file_get_contents(SCHEMA_PATH . 'projects/files.json'), true);
        $request = new Request();
        $request->setParent('file');
        $request->setFields($fields);

        $res = $request->getParameters('POST', [
            'grant_access_to' => '',
            'pending_file_ref' => 'test'
        ]);

        $this->assertMatchesJsonSnapshot($res);

        $res = $request->getParameters('POST', [
            'grant_access_to' => [1, 2, 3],
            'pending_file_ref' => 'test'
        ]);

        $this->assertMatchesJsonSnapshot($res);

        $res = $request->getParameters('POST', [
            'grant_access_to' => 1,
            'pending_file_ref' => 'test'
        ]);

        $this->assertMatchesJsonSnapshot($res);

        $res = $request->getParameters('POST', [
            'grant_access_to' => [],
            'pending_file_ref' => 'test'
        ]);

        $this->assertMatchesJsonSnapshot($res);

        try {
            $res = $request->getParameters('POST', [
                'pending_file_ref' => 'test',
                'grant_access_to' => [[10]],
            ]);
            $this->fail('Throw exception');
        } catch (Exception $e) {
            $this->assertEquals('Invalid value for field grant_access_to', $e->getMessage());
        }

        try {
            $res = $request->getParameters('POST', [
                'grant_access_to' => null,
            ]);
            $this->fail('Throw exception: ' . json_encode($res));
        } catch (Exception $e) {
            $this->assertEquals('Required field pending_file_ref', $e->getMessage());
        }
    }
}