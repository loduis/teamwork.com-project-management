<?php

namespace TeamWorkPm\Tests;

final class TimezoneTest extends TestCase
{
    public function testAll(): void
    {
        $this->assertGreaterThan(0, count($this->factory('timezone', [
           'GET /timezones' => true
        ])->all()));
    }
}