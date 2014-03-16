<?php

class ProjectTest extends TestCase
{
    private $id;

    private $model;

    public function setUp()
    {
        parent::setUp();
        $this->model = TeamWorkPm\Factory::build('project');
        $this->id = get_first_project_id();
    }

    /**
     * @dataProvider provider
     * @test
     */
    public function insert($data)
    {
        try {
          $data['category_id'] = get_first_project_category_id();
          $id = $this->model->save($data);
          $this->assertGreaterThan(0, $id);
          $project = $this->model->get($id);
          $this->assertEquals((int) $project->category->id, $data['category_id']);
        } catch (\TeamWorkPm\Exception $e) {
            $this->assertEquals('Project name taken', $e->getMessage());
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
            $data['category_id'] = 0;
            $this->assertTrue($this->model->save($data));
            $project = $this->model->get($this->id);
            $this->assertEquals((int) $project->category->id, $data['category_id']);
        } catch (\TeamWorkPm\Exception $e) {
            $this->assertEquals('Project name taken', $e->getMessage());
        }
    }

    /**
     * @expectedException        \TeamWorkPm\Exception
     * @dataProvider provider
     * @test
     */
    public function updateWithoutId($data)
    {
        $this->model->update($data);
    }

    /**
     * @test
     */
    public function star()
    {
        try {
            $this->assertTrue($this->model->star($this->id));
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @expectedException        \TeamWorkPm\Exception
     * @expectedExceptionMessage Invalid param id
     * @test
     */
    public function starInvalidProjectId()
    {
        $this->model->star(0);
    }


    /**
     * @depends star
     * @test
     */
    public function getStarred()
    {
        try {
            $projects = $this->model->getStarred();
            $this->assertGreaterThan(0, count($projects));
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @expectedException        \TeamWorkPm\Exception
     * @expectedExceptionMessage Invalid param id
     * @test
     */
    public function unStarInvalidProjectId()
    {
        $this->model->unStar(0);
    }

    /**
     * @test
     */
    public function unStar()
    {
        try {
            $this->assertTrue($this->model->unStar($this->id));
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @expectedException        \TeamWorkPm\Exception
     * @expectedExceptionMessage Invalid param id
     * @test
     */
    public function archiveInvalidProjectId()
    {
        $this->model->archive(0);
    }

    /**
     * @test
     */
    public function archive()
    {
        try {
            $this->assertTrue($this->model->archive($this->id));
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @depends archive
     * @test
     */
    public function getArchived()
    {
        try {
            $projects = $this->model->getArchived();
            $this->assertGreaterThan(0, count($projects));
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @expectedException        \TeamWorkPm\Exception
     * @expectedExceptionMessage Invalid param id
     * @test
     */
    public function activateInvalidProjectId()
    {
        $this->model->activate(0);
    }

    /**
     * @test
     */
    public function activate()
    {
        try {
            $this->assertTrue($this->model->activate($this->id));
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @depends activate
     * @test
     */
    public function getActive()
    {
        try {
            $projects = $this->model->getActive();
            $this->assertGreaterThan(0, count($projects));
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @test
     */
    public function get()
    {
        try {
            $project     = $this->model->get($this->id);
            $this->assertEquals($project->id, $this->id);
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
            $projects = $this->model->getAll();
            $this->assertGreaterThan(0, count($projects));
            $save = $projects->save(__DIR__ . '/build/projects');
            $this->assertTrue(is_numeric($save));
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }


    /**
     * @expectedException \TeamWorkPm\Exception
     * @test
     */
    public function deleteInvalidId()
    {
        $this->model->delete(0);
    }

    public function provider()
    {
        return [
            [
              [
                "name"        => "test project",
                "description" => "bla, bla, bla",
                "start_date"  => 20121110,
                "end_date"    => 20121210,
                "new_company" => "Test Company"
              ]
            ]
        ];
    }
}