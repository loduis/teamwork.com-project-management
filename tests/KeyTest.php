<?php

namespace TeamWorkPm\Tests;

use TeamWorkPm\Exception;
use TeamWorkPm\Factory;

class KeyTest extends TestCase
{
    /**
     * @test
     */
    public function setYourApiKey()
    {
        try {
            $project = Factory::build('project');
        } catch (Exception $e) {
            $this->assertEquals('Set your url and api key', $e->getMessage());
        }
    }
}
