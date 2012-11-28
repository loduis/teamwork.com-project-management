<?php

class CompanyTest extends TestCase
{
    private $model;
    private $id;
    private $projectId;

    public function setUp()
    {
        parent::setUp();
        $this->model     = TeamWorkPm::factory('company');
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
            $data['name'] .= rand(1, 100);
            $id = $this->model->save($data);
            $this->assertGreaterThan(0, $id);
        } catch (\TeamWorkPm\Exception $e) {
            $code = $e->getCode();
            switch ($code) {
              case \TeamWorkPm\Error::THIS_RESOURCE_ALREADY_EXISTS:
                $this->markTestSkipped($e->getMessage());
                break;
              default:
                $this->assertTrue(false, $e->getMessage());
                break;
            }
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
            $data['id'] = $this->id;
            $this->assertTrue($this->model->save($data));
        } catch (\TeamWorkPm\Exception $e) {
            $this->assertTrue(false, $e->getMessage());
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
            $this->assertTrue(false, $e->getMessage());
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
            $this->assertTrue(false, $e->getMessage());
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
            $this->assertTrue(false, $e->getMessage());
        }
    }

    public function provider()
    {
        return array(
            array(
              array(
                'name'        => 'Test Company - ',
                'address_one' => 'Bla, Bla, Bla',
                'address_two' => 'JAJA',
                'zip'         => '057',
                'city'        => 'Bogota',
                'state'       => 'Engativa',
                'countrycode' => 'CO',
                'phone'       => '25034030',
                'fax'         => 'No tengo',
                'web_address' => 'No tengo'
              )
            )
        );
    }
}