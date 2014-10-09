<?php

class Task_ListTest extends TestCase
{
    private $model;
    private $projectId;
    private $id;

    public function setUp()
    {
        parent::setUp();
        $this->model = TeamWorkPm\Factory::build('task/list');
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
            $this->model->save($data);
            $this->fail('An expected exception has not been raised.');
        } catch (Exception $e) {
            $this->assertEquals('Required field project_id', $e->getMessage());
        }
        try {
            $data['project_id']   = $this->projectId;
            $data['milestone_id'] = get_first_milestone_id($this->projectId);
            $id                   = $this->model->save($data);
            $this->assertGreaterThan(0, $id);
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @depends insert
     * @dataProvider provider
     * @test
     */
    public function update($data)
    {
        try {
            $data['id'] = $this->id;
            $this->assertTrue($this->model->save($data));
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @depends insert
     * @test
     */
    public function get()
    {
        try {
            $this->model->get(0);
            $this->fail('An expected exception has not been raised.');
        } catch (Exception $e) {
            $this->assertEquals('Invalid param id', $e->getMessage());
        }
        try {
            $list = $this->model->get($this->id);
            $this->assertEquals($this->id, $list->id);
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
        // task list without tasks
        try {
            $list = $this->model->get($this->id, false);
            $this->assertFalse(isset($list->todoItems));
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @depends insert
     * @test
     */
    public function getByProject()
    {
        try {
            $this->model->getByProject(0);
            $this->fail('An expected exception has not been raised.');
        } catch (Exception $e) {
            $this->assertEquals('Invalid param project_id', $e->getMessage());
        }
        try {
            $list = $this->model->getByProject($this->projectId);
            $this->assertGreaterThan(0, count($list));
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @depends insert
     * @test
     */
    public function getActiveByProject()
    {
        try {
            $list = $this->model->getByProject($this->projectId, 'active');
            $this->assertGreaterThan(0, count($list));
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }
    /**
     * @depends insert
     * @test
     */
    public function getUpcomingByProject()
    {
        try {
            $list = $this->model->getByProject($this->projectId, 'upcoming');
            $this->assertGreaterThan(0, count($list));
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @depends insert
     * @dataProvider provider
     * @test
     */
    public function reorder($data)
    {
        try {
            $data['project_id'] = $this->projectId;
            $this->model->save($data);
            $list = $this->model->getByProject($this->projectId);
            $ids = [];
            foreach ($list as $l) {
                $ids[] = $l->id;
            }
            shuffle($ids);
            $this->assertTrue($this->model->reorder($this->projectId, $ids));
            $list = $this->model->getByProject($this->projectId);
            $order = [];
            foreach ($list as $l) {
                $order[] = $l->id;
            }
            $this->assertEquals($ids, $order);
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    public function provider()
    {
        return [
            [
              [
                'name'       => 'Test Task List',
                'description' => 'Bla, Bla, Bla',
                'private'     => false,
                'pinned'      => false,
                'tracked'    => false,
              ]
            ]
        ];
    }
}
