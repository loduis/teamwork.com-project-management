<?php

class Task_ListTest extends TestCase
{
    private $model;
    private $projectId;
    private $id;

    public function setUp()
    {
        parent::setUp();
        $this->model = TeamWorkPm::factory('task/list');
        $this->projectId = get_first_project_id();
        $this->id        = get_first_task_list_id($this->projectId);

    }

    /**
     * @dataProvider provider
     * @test
     */
    public function insert($data)
    {
        try {
            $data['project_id']   = $this->projectId;
            $data['milestone_id'] = get_first_milestone_id($this->projectId);
            $id                   = $this->model->save($data);
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
     * @expectedException        \TeamWorkPm\Exception
     * @expectedExceptionMessage Invalid param project_id
     * @test
     */
    public function getByProjectWithinInvalidProjectId()
    {
        $this->model->getByProject(0);
    }

    /**
     * @expectedException        \TeamWorkPm\Exception
     * @expectedExceptionMessage Invalid param id
     * @test
     */
    public function getWithInvalidId()
    {
        $this->model->get(0);
    }

    /**
     *
     * @test
     */
    public function get()
    {
        try {
            $list = $this->model->get($this->id);
            $this->assertEquals($this->id, $list->id);
        } catch (\TeamWorkPm\Exception $e) {
            $this->assertTrue(false, $e->getMessage());
        }
    }

    /**
     *
     * @test
     */
    public function getByProject()
    {
        try {
            $list = $this->model->getByProject($this->projectId);
            $this->assertGreaterThan(0, count($list));
        } catch (\TeamWorkPm\Exception $e) {
            $this->assertTrue(false, $e->getMessage());
        }
    }

    /**
     *
     * @test
     */
    public function getActiveByProject()
    {
        try {
            $list = $this->model->getByProject($this->projectId, 'active');
            $this->assertGreaterThan(0, count($list));
        } catch (\TeamWorkPm\Exception $e) {
            $this->assertTrue(false, $e->getMessage());
        }
    }
    /**
     *
     * @test
     */
    public function getUpcomingByProject()
    {
        try {
            $list = $this->model->getByProject($this->projectId, 'upcoming');
            $this->assertGreaterThan(0, count($list));
        } catch (\TeamWorkPm\Exception $e) {
            $this->assertTrue(false, $e->getMessage());
        }
    }

    /**
     * @dataProvider provider
     * @test
     */
    public function reorder($data)
    {
        try {
            $data['project_id'] = $this->projectId;
            $this->model->save($data);
            $list = $this->model->getByProject($this->projectId);
            $ids = array();
            foreach ($list as $l) {
                $ids[] = $l->id;
            }
            shuffle($ids);
            $this->assertTrue($this->model->reorder($this->projectId, $ids));
            $list = $this->model->getByProject($this->projectId);
            $order = array();
            foreach ($list as $l) {
                $order[] = $l->id;
            }
            $this->assertEquals($ids, $order);
        } catch (\TeamWorkPm\Exception $e) {
            $this->assertTrue(false, $e->getMessage());
        }
    }

    public function provider()
    {
        return array(
            array(
              array(
                'name'       => 'Test Task List',
                'description' => 'Bla, Bla, Bla',
                'private'     => false,
                'pinned'      => false,
                'tracked'    => false,
              )
            )
        );
    }

}