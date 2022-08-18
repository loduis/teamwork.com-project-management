<?php

namespace TeamWorkPm\Tests;

use TeamWorkPm\Account;
use TeamWorkPm\Exception;
use TeamWorkPm\Factory;

class AccountTest extends TestCase
{
    /**
     * @var Account
     */
    private $model;

    public function setUp()
    {
        parent::setUp();
        $this->model = Factory::build('account');
    }

    /**
     * @test
     */
    public function get()
    {
        try {
            $account = $this->model->get();
            // $this->assertEquals($account->url, 'https://' . API_COMPANY. '.teamworkpm.net/');
            $this->assertEquals(getenv('API_COMPANY'), $account->code);
            // $this->assertEquals($project->id, $this->id);
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @test
     */
    public function authenticate()
    {
        try {
            $authenticate = $this->model->authenticate();
            // $this->assertEquals($account->url, 'https://' . API_COMPANY. '.teamworkpm.net/');
            $this->assertEquals(getenv('API_COMPANY'), $authenticate->code);
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }
}