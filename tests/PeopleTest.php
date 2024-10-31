<?php

namespace TeamWorkPm\Tests;

use TeamWorkPm\Exception;

final class PeopleTest extends TestCase
{
    /**
     * @dataProvider provider
     * @test
     */
    public function insert($data): void
    {
        $fail = $data;
        // =========== validate email address ========= //
        try {
            $fail['email_address'] = 'back@email_address';
            $this->factory('people')->save($fail);
            $this->fail('An expected exception has not been raised.');
        } catch (Exception $e) {
            $this->assertEquals(
                'Invalid value for field email_address',
                $e->getMessage()
            );
        }

        // =========== required fields ========= //
        $fail = [];
        $required = [
            'email_address',
            'first_name',
            'last_name',
            'user_name',
        ];
        foreach ($required as $field) {
            try {
                $this->factory('people')->save($fail);
            } catch (Exception $e) {
                $this->assertEquals(
                    'Required field ' . $field,
                    $e->getMessage()
                );
                $fail[$field] = $data[$field];
            }
        }

        $this->assertEquals(10, $this->factory('people', [
            'POST /people' => fn($data) => $this->assertMatchesJsonSnapshot($data)
        ])->save($data));


        $data['project_id'] = TPM_PROJECT_ID;
        $data['permissions'] = [
            'project_administrator' => true
        ];

        $this->assertEquals(10, $this->factory('people', [
            'POST /people' => fn($data) => $this->assertMatchesJsonSnapshot($data),
            'POST /projects/967489/people/10' => true,
            'PUT /projects/967489/people/10' => true
        ])->save($data));
    }

    /**
     * @depends insert
     * @dataProvider provider
     * @test
     */
    public function update($data): void
    {
        try {
            $data['id'] = null;
            $this->assertTrue($this->factory('people')->save($data));
        } catch (Exception $e) {
            $this->assertEquals('Required field id', $e->getMessage());
        }

        $fail = [];
        // =========== validate email address ========= //
        try {
            $fail['id'] = TPM_TEST_ID;
            $fail['email_address'] = 'back@email_address';
            $this->assertTrue($this->factory('people')->save($fail));
        } catch (Exception $e) {
            $this->assertEquals(
                'Invalid value for field email_address',
                $e->getMessage()
            );
        }
        try {
            $data['id'] = TPM_TEST_ID;
            $data['company_id'] = TPM_COMPANY_ID;
            $this->assertTrue($this->factory('people', [
                'PUT /people/' . TPM_TEST_ID => fn($data) => $this->assertMatchesJsonSnapshot($data)
            ])->save($data));
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }

        $this->assertTrue($this->factory('people', [
            'PUT /people/' . TPM_TEST_ID => fn($data) => $this->assertMatchesJsonSnapshot($data)
        ])->save([
            'id' => TPM_TEST_ID,
            'address' => [
                'line1' => 'Main',
                'line2' => 'Second',
                'country_code' => 'CO',
                'city' => 'Manizales',
                'state' => 'Bolivar',
                'zip_code' => '01011'
            ],
            'working_hours' => [
                [
                    'weekday' => 'sunday',
                    'task_hours' => 3
                ],
                [
                    'weekday' => 'monday',
                    'task_hours' => 3
                ]
            ]
        ]));

    }

    /**
     * @depends insert
     * @test
     */
    public function get(): void
    {
        $this->assertEquals(
            "Php",
            $this->factory('people', [
                'GET /people/' . TPM_USER_ID => true
            ])->get(TPM_USER_ID)->firstName
        );
    }

    /**
     * @depends insert
     * @test
     */
    public function getAll(): void
    {
        $this->assertGreaterThan(0,
            count($this->factory('people', [
                'GET /people' => true
            ])->all())
        );
    }

    /**
     * @depends insert
     * @test
     */
    public function getByProject(): void
    {
        $this->assertGreaterThan(0,
            count($this->factory('people', [
                'GET /projects/' . TPM_PROJECT_ID . '/people' => true
            ])->getByProject(TPM_PROJECT_ID))
        );
    }

    /**
     * @depends insert
     * @test
     */
    public function getByCompany(): void
    {
        $this->assertGreaterThan(0, count($this->factory('people')->getByCompany(TPM_COMPANY_ID)));

    }

    /**
     * @depends insert
     * @test
     */
    public function getApiKeys(): void
    {
        $this->assertGreaterThan(0, count($this->factory('people')->getApiKeys()));
    }

    /**
     * @depends insert
     * @test
     */
    public function getAvailableFor(): void
    {
        $this->assertGreaterThan(0, count($this->factory('people')
            ->getAvailableFor('tasks', ['project_id' => TPM_PROJECT_ID])
        ));

        $this->assertGreaterThan(0, count($this->factory('people')
            ->getAvailableFor('messages', ['project_id' => TPM_PROJECT_ID])
        ));

        $this->assertGreaterThan(0, count($this->factory('people')
            ->getAvailableFor('milestones', ['project_id' => TPM_PROJECT_ID])
        ));

        $this->assertGreaterThan(0, count($this->factory('people')
            ->getAvailableFor('files', ['project_id' => TPM_PROJECT_ID])
        ));

        $this->assertGreaterThan(0, count($this->factory('people')
            ->getAvailableFor('links', ['project_id' => TPM_PROJECT_ID])
        ));

        $this->assertGreaterThan(0, count($this->factory('people')
            ->getAvailableFor('notebooks', ['project_id' => TPM_PROJECT_ID])
        ));

        // TODO Add unit test for calendar_events
    }

    /**
     * @test
     */
    public function getMe(): void
    {
        $me = $this->factory('people')->getMe();

        $this->assertEquals('test@gmail.com', $me->userName);
    }

    /**
     * @test
     */
    public function getStats(): void
    {
        $stats = $this->factory('people')->getStats();

        $this->assertTrue(isset($stats->tasks));
    }

    /**
     * @test
     *
     * @return void
     */
    public function delete()
    {
        $this->assertTrue($this->factory('people', [
            'DELETE /people/' . TPM_USER_ID => true
        ])->delete(TPM_USER_ID));
    }

    public function provider()
    {
        return [
            [
                [
                    'first_name' => 'Test',
                    'last_name' => 'User',
                    'user_name' => 'test',
                    'email_address' => 'test@hotmail.com',
                ],
            ],
        ];
    }
}
