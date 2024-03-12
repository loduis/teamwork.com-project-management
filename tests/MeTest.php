<?php

namespace TeamWorkPm\Tests;

use TeamWorkPm\Exception;
use TeamWorkPm\Factory;

final class MeTest extends TestCase
{
    private $model;

    protected function setUp(): void
    {
        parent::setUp();
        $this->model = Factory::build('me');
    }

    /**
     * @test
     */
    public function get(): void
    {
        try {
            $me = $this->model->get();
            $this->assertEquals('loduis@gmail.com', $me->userName);
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }
}
