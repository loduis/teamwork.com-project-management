<?php

class KeyTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function setYourApiKey()
    {
        try {
            $project = TeamWorkPm\Factory::build('project');
        } catch (\TeamWorkPm\Exception $e) {
            $this->assertEquals('Set your url and api key', $e->getMessage());
        }
    }
}
