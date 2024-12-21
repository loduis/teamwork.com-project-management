<?php

namespace TeamWorkPm\Tests;

use TeamWorkPm\Exception;

final class CompanyTest extends TestCase
{
    /**
     * @dataProvider provider
     * @test
     */
    public function insertValidateCountry($data): void
    {
        $data['country_code'] = 'BAD CODE';
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid value for field country_code');
        $this->factory('company')->save($data);
    }

    /**
     * @dataProvider provider
     * @test
     */
    public function insert($data): void
    {
        $data['remove_logo'] = true;

        $this->assertEquals(10, $this->factory('company', [
            'POST /companies' => fn($data) => $this->assertMatchesJsonSnapshot($data)
        ])->save($data));
    }

    /**
     * @depends insert
     * @dataProvider provider
     * @test
     */
    public function update($data): void
    {
        $data['countrycode'] = $data['country_code'];
        unset($data['country_code']);
        $data['remove_logo'] = true;
        $data['private_notes'] = 'Private notes';
        $data['id'] = 10;

        $this->assertTrue($this->factory('company', [
            'PUT /companies/10' => fn($data) => $this->assertMatchesJsonSnapshot($data)
        ])->save($data));
    }

    /**
     * @depends insert
     * @test
     */
    public function get(): void
    {
        try {
            $this->factory('company')->get(0);
            $this->fail('An expected exception has not been raised.');
        } catch (Exception $e) {
            $this->assertEquals('Invalid param id', $e->getMessage());
        }
        $this->assertEquals(
            "Php's Company",
            $this->factory('company')->get(TPM_COMPANY_ID)->name
        );
    }

    /**
     * @test
     */
    public function getAll(): void
    {
        $this->assertGreaterThan(0, count($this->factory('company')->all()));
    }

    /**
     * @test
     */
    public function getByProject(): void
    {

        $this->assertGreaterThan(0, count(
                $this->factory('company')->getByProject(TPM_PROJECT_ID_1)
            )
        );
    }

    public function provider()
    {
        return [
            [
                [
                    'name' => 'Test Company',
                    'address_one' => 'Bla, Bla, Bla',
                    'address_two' => 'JAJA',
                    'zip' => '057',
                    'city' => 'Bogota',
                    'state' => 'Engativa',
                    'country_code' => 'CO',
                    'phone' => '25034030',
                    'fax' => 'No tengo',
                    'website' => 'https://test.com',
                ],
            ],
        ];
    }
}
