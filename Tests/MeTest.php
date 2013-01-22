<?php

class MeTest extends TestCase
{

    private $model;

    public function setUp()
    {
        parent::setUp();
        $this->model = TeamWorkPm::factory('me');
    }


    /**
     * @test
     */
    public function get()
    {
        try {
            $me = $this->model->get();
            $this->assertEquals('loduis@gmail.com', $me->userName);
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }
}