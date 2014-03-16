<?php

class TimeTest extends TestCase
{
    private $model;
    private $taskId;
    private $projectId;
    private $id;

    public function setUp()
    {
        parent::setUp();
        $this->model     = TeamWorkPm\Factory::build('time');
        $this->projectId = get_first_project_id();
        $task_list_id    = get_first_task_list_id($this->projectId);
        $this->taskId    = get_first_task_id($task_list_id);
        $this->id        = get_first_time_id($this->taskId);

    }

    /**
     * @dataProvider provider
     * @test
     */
    public function insertInProject($data)
    {
        try {
            $this->model->save($data);
            $this->fail('An expected exception has not been raised.');
        } catch (Exception $e) {
            $this->assertEquals('Required field project_id or task_id', $e->getMessage());
        }
        try {
            $data['project_id'] = $this->projectId;
            $data['person_id']  = get_first_person_id($this->projectId);
            $id                 = $this->model->save($data);
            $this->assertGreaterThan(0, $id);
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @dataProvider provider
     * @test
     */
    public function insertInTask($data)
    {
        try {
            $data['task_id'] = $this->taskId;
            $data['person_id']  = get_first_person_id($this->projectId);
            $id                 = $this->model->save($data);
            $this->assertGreaterThan(0, $id);
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @dataProvider provider
     * @test
     */
    public function update($data)
    {
        try {
            $data['id'] = $this->id;
            // up 24
            $data['hours'] = 50;
            $this->assertTrue($this->model->save($data));
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     *
     * @test
     */
    public function getAll()
    {
        try {
            $times = $this->model->getAll();
            $this->assertGreaterThan(0, count($times));
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
            $times = $this->model->getByProject($this->projectId);
            $this->assertGreaterThan(0, count($times));
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     *
     * @test
     */
    public function getByTask()
    {
        try {
            $times = $this->model->getByTask(0);
            $this->fail('An expected exception has not been raised.');
        } catch (Exception $e) {
            $this->assertEquals('Invalid param task_id', $e->getMessage());
        }
        try {
            $times = $this->model->getByTask($this->taskId);
            $this->assertGreaterThan(0, count($times));
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @dataProvider provider
     * @test
     */
    public function get($data)
    {
        try {
            $times = $this->model->get(0);
            $this->fail('An expected exception has not been raised.');
        } catch (Exception $e) {
            $this->assertEquals('Invalid param id', $e->getMessage());
        }
        try {
            $time = $this->model->get($this->id);
            $this->assertEquals($this->id, $time->id);
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }


    public function provider()
    {
        return [
            [
              [
                'description' => 'Test Time',
                'person_id'   => null, // this is a required field
                'date'  => date('Ymd'),
                'hours'     => 5,
                'minutes' => 30,
                'time' => '08:30',
                'isbillable' => true
              ]
            ]
        ];
    }
}
