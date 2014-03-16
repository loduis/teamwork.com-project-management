<?php

class ActivityTest extends TestCase
{
    private $projectId;

    private $model;

    public function setUp()
    {
        parent::setUp();
        $this->model     = TeamWorkPm\Factory::build('activity');
        $this->projectId = get_first_project_id();
    }


    /**
     * @test
     */
    public function getAll()
    {
        try {
            $activity = $this->model->getAll();
            $this->assertGreaterThan(0, count($activity));
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
        // stared
        try {
            $project = TeamWorkPm\Factory::build('project');
            $project->star($this->projectId);
            $activity = $this->model->getAll(5, true);
            $this->assertGreaterThan(0, count($activity));
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     *
     * @test
     */
    public function getByProject()
    {
        try {
            $times = $this->model->getByProject(0);
            $this->fail('An expected exception has not been raised.');
        } catch (Exception $e) {
            $this->assertEquals('Invalid param project_id', $e->getMessage());
        }
        try {
            $activity = $this->model->getByProject($this->projectId, 5);
            $this->assertGreaterThan(0, count($activity));
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }
}