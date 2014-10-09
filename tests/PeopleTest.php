<?php

class PeopleTest extends TestCase
{
    private $model;
    private static $id;

    public function setUp()
    {
        parent::setUp();
        $this->model = TeamWorkPm\Factory::build('people');
    }

    /**
     * @dataProvider provider
     * @test
     */
    public function insert($data)
    {
        $fail = [];
        // =========== validate email address ========= //
        try {
            $fail['email_address'] = 'back@email_address';
            $this->model->save($fail);
            $this->fail('An expected exception has not been raised.');
        } catch (\TeamWorkPm\Exception $e) {
            $this->assertEquals(
                'Invalid value for field email_address' ,
                $e->getMessage()
            );
        }
        // =========== required fields ========= //
        $fail['email_address'] = null;
        $required = [
            'first_name',
            'last_name',
            'email_address',
            'user_name',
        ];
        foreach ($required as $field) {
            try {
                $this->model->save($fail);
                $this->fail('An expected exception has not been raised.');
            } catch (\TeamWorkPm\Exception $e) {
                $this->assertEquals(
                    'Required field ' . $field,
                    $e->getMessage()
                );
                $fail[$field] = $data[$field];
            }
        }

        // =========== validate im service ========= //
        try {
            $fail['im_service'] = 'invalid_im';
            $this->model->save($fail);
            $this->fail('An expected exception has not been raised.');
        } catch (\TeamWorkPm\Exception $e) {
            $this->assertEquals(
                'Invalid value for field im_service' ,
                $e->getMessage()
            );
        }
        $fail['im_service'] = null;

        // =========== validate im user language ========= //
        try {
            $fail['user_language'] = 'invalid_lang';
            $this->model->save($fail);
            $this->fail('An expected exception has not been raised.');
        } catch (\TeamWorkPm\Exception $e) {
            $this->assertEquals(
                'Invalid value for field user_language' ,
                $e->getMessage()
            );
        }

        $fail['user_language'] = null;

        // =========== validate im user language ========= //
        try {
            $fail['date_format'] = 'invalid_date_format';
            $this->model->save($fail);
            $this->fail('An expected exception has not been raised.');
        } catch (\TeamWorkPm\Exception $e) {
            $this->assertEquals(
                'Invalid value for field date_format' ,
                $e->getMessage()
            );
        }

        $fail['date_format'] = null;

        // =========== validate email in use ========= //
        try {
            $person = null;
            foreach ($this->model->getAll() as $person) {
                break;
            }
            $fail['email_address'] = $person->emailAddress;
            $this->model->save($fail);
            $this->fail('An expected exception has not been raised.');
        } catch (\TeamWorkPm\Exception $e) {
            $this->assertEquals(
                'Email in use',
                $e->getMessage()
            );
        }

        // =========== Login already taken ========= //
        try {
            $fail['email_address'] = $data['email_address'];
            $fail['user_name']     = $person->userName;
            $this->model->save($fail);
            $this->fail('An expected exception has not been raised.');
        } catch (\TeamWorkPm\Exception $e) {
            $this->assertEquals(
                'Login already taken',
                $e->getMessage()
            );
        }


        // =========== insert now ========= //
        try {
            // and add to this project
            $data['project_id'] = get_first_project_id();
            // change this permissions on insert
            $data['permissions'] = [
                'view_risk_register'=> 0,
                'view_invoices'     => 0
            ];
            self::$id = $this->model->save($data);
            $this->assertGreaterThan(0, self::$id);
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @depends insert
     * @dataProvider provider
     * @test
     */
    public function update($data)
    {
        try {
            $data['id'] = null;
            $this->assertTrue($this->model->save($data));
        } catch (\TeamWorkPm\Exception $e) {
            $this->assertEquals('Required field id', $e->getMessage());
        }
        $fail = [];
        // =========== validate email address ========= //
        try {
            $fail['id'] = self::$id;
            $fail['email_address'] = 'back@email_address';
            $this->model->save($fail);
            $this->fail('An expected exception has not been raised.');
        } catch (\TeamWorkPm\Exception $e) {
            $this->assertEquals(
                'Invalid value for field email_address' ,
                $e->getMessage()
            );
        }
        try {
            $data['id'] = self::$id;
            // and add to this project
            $data['project_id'] = get_first_project_id();
            // change this permissions on insert
            $data['permissions'] = [
                'view_risk_register'=> 1,
                'view_invoices'     => 1
            ];
            $this->assertTrue($this->model->save($data));
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @depends insert
     * @test
     */
    public function get()
    {
        try {
            $this->model->get(0);
            $this->fail('An expected exception has not been raised.');
        } catch (Exception $e) {
            $this->assertEquals('Invalid param id', $e->getMessage());
        }
        try {
            $people = $this->model->get(self::$id);
            $this->assertEquals(self::$id, $people->id);
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
        // get people in project
        try {
            $project_id = get_first_project_id();
            $people = $this->model->get(self::$id, $project_id);
            $this->assertEquals(self::$id, $people->id);
            $this->assertTrue(isset($people->permissions));
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @depends insert
     * @test
     */
    public function getAll()
    {
         try {
            $people  = $this->model->getAll();
            $this->assertGreaterThan(1, count($people));
         } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
         }
    }

    /**
     * @depends insert
     * @test
     */
    public function getByProject()
    {
         try {
            $project_id = get_first_project_id();
            if ($project_id) {
                $people  = $this->model->getByProject($project_id);
                $this->assertGreaterThan(0, count($people));
            }
         } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
         }
    }

    /**
     * @depends insert
     * @test
     */
    public function getByCompany()
    {
         try {
            $company_id = get_first_company_id();
            $people  = $this->model->getByCompany($company_id);
            $this->assertGreaterThan(1, count($people));
         } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
         }
    }

    public function provider()
    {
        return [
            [
              [
                'first_name'  => "Test",
                'last_name'   => 'User',
                'user_name'     => 'test',
                'email_address' => 'loduis@hotmail.com',
                'password'      => 'El loco de la calle',
                'address_one'   => 'Cra 45 # 40-10',
                'send_welcome_email' => false
              ]
            ]
        ];
    }
}
