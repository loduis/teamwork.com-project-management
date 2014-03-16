<?php

class AccountTest extends TestCase
{

    private $model;

    public function setUp()
    {
        parent::setUp();
        $this->model = TeamWorkPm\Factory::build('account');
    }


    /**
     * @test
     */
    public function get()
    {
        try {
            $account = $this->model->get();
            $this->assertEquals($account->url, 'https://' . API_COMPANY
                . '.teamworkpm.net/');
            $this->assertEquals($account->code, API_COMPANY);
            // $this->assertEquals($project->id, $this->id);
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     *
     * @test
     */
    public function authenticate()
    {
        try {
            $authenticate = $this->model->authenticate();
            $this->assertEquals($authenticate->url, 'https://' . API_COMPANY
                . '.teamworkpm.net/');
            $this->assertEquals($authenticate->code, API_COMPANY);
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }
}