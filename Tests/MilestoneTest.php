<?php

class MilestoneTest extends TestCase
{
    private $model;
    private $id;
    private $projectId;

    public function setUp()
    {
        parent::setUp();
        $this->model     = TeamWorkPm::factory('milestone');
        $this->projectId = get_first_project_id();
        $this->id        = get_first_milestone_id($this->projectId);
    }

    /**
     * @dataProvider provider
     * @test
     */
    public function insert($data)
    {
        try {
            $data['project_id'] = $this->projectId;
            $data['responsible_party_ids'] = get_first_people_id($this->projectId);
            $id = $this->model->save($data);
            $this->assertGreaterThan(0, $id);
        } catch (\TeamWorkPm\Exception $e) {
            $this->assertTrue(false, $e->getMessage());
        }
    }

    /**
     * @expectedException        \TeamWorkPm\Exception
     * @expectedExceptionMessage Required field project_id
     * @dataProvider provider
     * @test
     */
    public function insertWithoutProjectId($data)
    {
        $this->model->save($data);
    }


    /**
     * @dataProvider provider
     * @test
     */
    public function update($data)
    {
        try {
            $data['id'] = $this->id;
            $this->assertTrue($this->model->save($data));
        } catch (\TeamWorkPm\Exception $e) {
            $this->assertTrue(false, $e->getMessage());
        }
    }

    /**
     *
     * @test
     */
    public function get()
    {
        try {
            $milestone = $this->model->get($this->id);
            $this->assertEquals($this->id, $milestone->id);
        } catch (\TeamWorkPm\Exception $e) {
            $this->assertTrue(false, $e->getMessage());
        }
    }

    /**
     *
     * @test
     */
    public function getAllWithoutProject()
    {
        try {
            $milestones = $this->model->getAll();
            $this->assertGreaterThan(0, count($milestones));
        } catch (\TeamWorkPm\Exception $e) {
            $this->assertTrue(false, $e->getMessage());
        }
    }

    /**
     * @test
     */
    public function complete()
    {
        try {
            $this->assertTrue($this->model->complete($this->id));
        } catch (\TeamWorkPm\Exception $e) {
            $this->assertTrue(false, $e->getMessage());
        }
    }

    /**
     *
     * @test
     */
    public function getCompleted()
    {
        try {
            $milestones = $this->model->getByProject(
                $this->projectId,
                'completed'
            );
            $this->assertGreaterThan(0, count($milestones));
        } catch (\TeamWorkPm\Exception $e) {
            $this->assertTrue(false, $e->getMessage());
        }
    }

    /**
     * @test
     */
    public function unComplete()
    {
        try {
            $this->assertTrue($this->model->unComplete($this->id));
        } catch (\TeamWorkPm\Exception $e) {
            $this->assertTrue(false, $e->getMessage());
        }
    }

    /**
     *
     * @test
     */
    public function getIncomplete()
    {
        try {
            $milestones = $this->model->getByProject(
                $this->projectId,
                'incomplete'
            );
            $this->assertGreaterThan(0, count($milestones));
        } catch (\TeamWorkPm\Exception $e) {
            $this->assertTrue(false, $e->getMessage());
        }
    }

    /**
     * @dataProvider provider
     * @test
     */
    public function markAsLate($data)
    {
        try {
            $data['id'] = $this->id;
            $data['deadline'] = date('Ymd', strtotime('-10 days'));
            $this->assertTrue($this->model->save($data));
        } catch (\TeamWorkPm\Exception $e) {
            $this->assertTrue(false, $e->getMessage());
        }
    }

    /**
     * @depends markAsLate
     * @test
     */
    public function getLate()
    {
        try {
            $milestones = $this->model->getByProject(
                $this->projectId,
                'late'
            );
            $this->assertGreaterThan(0, count($milestones));
        } catch (\TeamWorkPm\Exception $e) {
            $this->assertTrue(false, $e->getMessage());
        }
    }

    /**
     * @dataProvider provider
     * @test
     */
    public function markAsUpcoming($data)
    {
        try {
            $data['id'] = $this->id;
            $data['deadline'] = date('Ymd', strtotime('-10 days'));
            $this->assertTrue($this->model->save($data));
        } catch (\TeamWorkPm\Exception $e) {
            $this->assertTrue(false, $e->getMessage());
        }
    }

    /**
     * @depends markAsLate
     * @test
     */
    public function getUpcoming()
    {
        try {
            $milestones = $this->model->getByProject(
                $this->projectId,
                'upcoming'
            );
            $this->assertGreaterThan(0, count($milestones));
        } catch (\TeamWorkPm\Exception $e) {
            $this->assertTrue(false, $e->getMessage());
        }
    }


    public function provider()
    {
        return array(
            array(
              array(
                'title'       => 'Test milestone',
                'description' => 'Bla, Bla, Bla',
                'deadline'    => date('Ymd', strtotime('+10 day')),
                'notify'      => false,
                'reminder'    => false,
                'private'     => false
              )
            )
        );
    }
}