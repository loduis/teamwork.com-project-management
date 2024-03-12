<?php

namespace TeamWorkPm\Tests;

use TeamWorkPm\Exception;
use TeamWorkPm\Factory;

final class KeyTest extends TestCase
{
    /**
     * @test
     */
    public function setYourApiKey(): void
    {
        try {
            $project = Factory::build('project');
        } catch (Exception $e) {
            $this->assertEquals('Set your url and api key', $e->getMessage());
        }
    }
}
