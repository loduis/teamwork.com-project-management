<?php

class NotebookTest extends TestCase
{
    private $model;
    private $id;
    private $projectId;

    public function setUp()
    {
        parent::setUp();
        $this->model     = TeamWorkPm\Factory::build('notebook');
        $this->projectId = get_first_project_id();
        $this->id        = get_first_notebook_id($this->projectId);
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
            $data['project_id'] = $this->projectId;
            $id = $this->model->save($data);
            $this->assertGreaterThan(0, $id);
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     *
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
            $notebook = $this->model->get($this->id);
            $this->assertEquals($this->id, $notebook->id);
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
            $notebooks = $this->model->getAll();
            $this->assertGreaterThan(0, count($notebooks));
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }

        // get with content

    }

    /**
     *
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
            $notebooks = $this->model->getByProject($this->projectId);
            $this->assertGreaterThan(0, count($notebooks));
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
        // get with content

    }


    /**
     * @test
     */
    public function lock()
    {
        try {
            $this->model->lock(0);
            $this->fail('An expected exception has not been raised.');
        } catch (Exception $e) {
            $this->assertEquals('Invalid param id', $e->getMessage());
        }
        try {
            $this->assertTrue($this->model->lock($this->id));
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @test
     */
    public function unlook()
    {
        try {
            $this->model->unlock(0);
            $this->fail('An expected exception has not been raised.');
        } catch (Exception $e) {
            $this->assertEquals('Invalid param id', $e->getMessage());
        }
        try {
            $this->assertTrue($this->model->unlock($this->id));
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    public function provider()
    {
        return [
            [
              [
                'name'          => 'Test notebook',
                'description'   => 'Bla, Bla, Bla',
                'content'       => '<b>Nada</b>, <i>nada</i>, nada',
                'notify'        => false,
                'category_id'   => 0,
                'category_name' => 'New Notebook category.',
                'private'       => false
              ]
            ]
        ];
    }
}
