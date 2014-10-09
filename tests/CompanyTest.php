<?php

class CompanyTest extends TestCase
{
    private $model;
    private $id;
    private $projectId;

    public function setUp()
    {
        parent::setUp();
        $this->model     = TeamWorkPm\Factory::build('company');
        $this->projectId = get_first_project_id();
        $this->id        = get_first_company_id();
    }

    /**
     * @dataProvider provider
     * @test
     */
    public function insert($data)
    {
        try {
            $countrycode = $data['countrycode'];
            $data['countrycode'] = 'BAD CODE';
            $this->model->save($data);
            $this->fail('An expected exception has not been raised.');
        } catch (\TeamWorkPm\Exception $e) {
            $this->assertEquals(
                "Invalid value for field countrycode",
                $e->getMessage()
            );
        }
        $data['countrycode'] = $countrycode;
        try {
            $data['name'] = rand_string($data['name']);
            $id = $this->model->save($data);
            $this->assertGreaterThan(0, $id);
        } catch (\TeamWorkPm\Exception $e) {
            $this->assertEquals('Already exists', $e->getMessage());
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
            $data['name'] = rand_string($data['name']);
            $data['id'] = $this->id;
            $this->assertTrue($this->model->save($data));
        } catch (\TeamWorkPm\Exception $e) {
            $this->assertEquals('Already exists', $e->getMessage());
        }
    }

    /**
     * @depends insert
     * @test
     */
    public function get()
    {
        try {
            $times = $this->model->get(0);
            $this->fail('An expected exception has not been raised.');
        } catch (\TeamWorkPm\Exception $e) {
            $this->assertEquals('Invalid param id', $e->getMessage());
        }
        try {
            $company = $this->model->get($this->id);
            $this->assertEquals($this->id, $company->id);
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     *
     * @test
     */
    public function getAll()
    {
        try {
            $companies = $this->model->getAll();
            $this->assertGreaterThan(0, count($companies));
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     *
     * @test
     */
    public function getByProject()
    {
        try {
            $this->model->getByProject(0);
            $this->fail('An expected exception has not been raised.');
        } catch (Exception $e) {
            $this->assertEquals('Invalid param project_id', $e->getMessage());
        }
        try {
            $companies = $this->model->getByProject($this->projectId);
            $this->assertGreaterThan(0, count($companies));
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    public function provider()
    {
        return [
            [
              [
                'name'        => 'Test Company',
                'address_one' => 'Bla, Bla, Bla',
                'address_two' => 'JAJA',
                'zip'         => '057',
                'city'        => 'Bogota',
                'state'       => 'Engativa',
                'countrycode' => 'CO',
                'phone'       => '25034030',
                'fax'         => 'No tengo',
                'web_address' => 'No tengo'
              ]
            ]
        ];
    }
}
