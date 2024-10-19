<?php

namespace TeamWorkPm\Tests;

class ActivityTest extends TestCase
{

    /**
     * @test
     */
    public function getAll()
    {
        $activities = $this->getTpm('activity')->all();
        $this->assertCount(4, $activities);
        $this->assertEquals('test project', $activities[0]->projectName);
    }

    /**
     * @test
     */
    public function getByProject()
    {
        $activities = $this->getTpm('activity')->getByProject(967489);
        $this->assertCount(3, $activities);
        $this->assertEquals('new', $activities[0]->activitytype);
    }

    /**
     * @test
     */
    public function getByTask()
    {
        $activities = $this->getTpm('activity')->getByTask(33512354);
        $this->assertCount(1, $activities);
        $this->assertEquals('391604', $activities[0]->userid);
    }
}
