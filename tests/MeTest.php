<?php

namespace TeamWorkPm\Tests;

final class MeTest extends TestCase
{
    /**
     * @test
     */
    public function get(): void
    {
        $me = $this->getTpm('me')->get();

        $this->assertEquals('test@gmail.com', $me->userName);
    }
}
