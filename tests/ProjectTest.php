<?php

namespace TeamWorkPm\Tests;

use TeamWorkPm\Project;

class ProjectTest extends TestCase
{
    public function testAll()
    {
        $projects = Project::all();
        $this->assertCount(2, $projects);
    }

    public function testOne()
    {
        $project = Project::get(967518);
        $this->assertEquals('2023-11-29T13:10:22Z', $project->createdOn);
        $this->assertEquals('2023-11-29T13:10:22Z', $project['created_on']);
        $this->assertEquals('2023-11-29T13:10:22Z', $project->lastChangedOn);
        $this->assertEquals('2023-11-29T13:10:22Z', $project['last_changed_on']);
        $this->assertEquals(true, $project->isBillable);
        $this->assertEquals(true, $project['is_billable']);
        $this->assertEquals('Hi', $project->announcementHtml);
        $this->assertEquals('Hi', $project['announcement_html']);
    }
}