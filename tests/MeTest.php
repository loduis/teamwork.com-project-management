<?php

namespace TeamWorkPm\Tests;

final class MeTest extends TestCase
{
    public function testGet(): void
    {
        $me = $this->factory('me')->get();

        $this->assertEquals('test@gmail.com', $me->userName);
    }

    /**
     * @test
     */
    public function getStats(): void
    {
        $stats = $this->factory('me')->getStats();

        $this->assertTrue(isset($stats->tasks));
    }


    public function testGetTimers(): void
    {
        $timers = $this->factory('me')->getTimers();

        $this->assertCount(0, $timers);
    }
}