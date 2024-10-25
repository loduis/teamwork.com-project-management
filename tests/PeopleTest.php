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
            $this->postTpm('people')->save($fail);
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
            'first_name',
            'last_name',
            'email_address',
            'user_name',
        ];
        foreach ($required as $field) {
            try {
                $this->postTpm('people')->save($fail);
            } catch (Exception $e) {
                $this->assertEquals(
                    'Required field ' . $field,
                    $e->getMessage()
                );
                $fail[$field] = $data[$field];
            }
        }

        $this->assertEquals(10, $this->postTpm('people', function ($headers) {
            $people = $headers['X-Params'];
            $this->assertObjectHasProperty('email-address', $people);
            $this->assertObjectHasProperty('first-name', $people);
        })->save($data));

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
            $this->assertTrue($this->putTpm('people')->save($data));
        } catch (Exception $e) {
            $this->assertEquals('Required field id', $e->getMessage());
        }

        $fail = [];
        // =========== validate email address ========= //
        try {
            $fail['id'] = 10;
            $fail['email_address'] = 'back@email_address';
            $this->assertTrue($this->putTpm('people')->save($fail));
        } catch (Exception $e) {
            $this->assertEquals(
                'Invalid value for field email_address',
                $e->getMessage()
            );
        }
        try {
            $data['id'] = 10;
            $data['company_id'] = TPM_COMPANY_ID;
            $this->assertTrue($this->putTpm('people', function ($headers) {
                $people = $headers['X-Params'];
                $this->assertObjectHasProperty('email-address', $people);
                $this->assertObjectHasProperty('company-id', $people);
            })->save($data));
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @depends insert
     * @test
     */
    public function get(): void
    {
        $this->assertEquals(
            "Php",
            $this->getTpm('people')->get(TPM_USER_ID)->firstName
        );
    }

    /**
     * @depends insert
     * @test
     */
    public function getAll(): void
    {
        $this->assertGreaterThan(0, count($this->getTpm('people')->all()));
    }

    /**
     * @depends insert
     * @test
     */
    public function getByProject(): void
    {
        $this->assertGreaterThan(0, count($this->getTpm('people')->getByProject(TPM_PROJECT_ID)));
    }

    /**
     * @depends insert
     * @test
     */
    public function getByCompany(): void
    {
        $this->assertGreaterThan(0, count($this->getTpm('people')->getByCompany(TPM_COMPANY_ID)));

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
