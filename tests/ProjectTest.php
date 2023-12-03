<?php

namespace TeamWorkPm\Tests;

use TeamWorkPm\Company;
use TeamWorkPm\Project;

class ProjectTest extends TestCase
{
    public function testAll(): void
    {
        $projects = Project::all();
        $this->assertCount(2, $projects);
    }

    public function testOne(): void
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

        $companies = $project->companies();

        $this->assertCount(1, $companies);

        // TODO Need an Collection of Company
        $company = $companies->first();

        $this->assertInstanceOf(Company::class, $company);

        $this->assertEquals(1370007, $company->id);
    }
}