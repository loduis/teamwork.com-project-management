<?php

class Category_NotebookTest extends TestCase
{
    private $model;
    private $projectId;
    private $id;

    public function setUp()
    {
        parent::setUp();
        $this->model     = TeamWorkPm\Factory::build('category/notebook');
        $this->projectId = get_first_project_id();
        $this->id        = get_first_notebook_category_id($this->projectId);
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
            $data['project_id']  = $this->projectId;
            $id = $this->model->save($data);
            $this->assertGreaterThan(0, $id);
        } catch (\TeamWorkPm\Exception $e) {
            $this->assertEquals('Already exists', $e->getMessage());
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
            $this->assertTrue($this->model->save($data));
        } catch (\TeamWorkPm\Exception $e) {
            $this->assertEquals('Already exists', $e->getMessage());
        }
    }

    /**
     *
     * @test
     */
    public function get()
    {
        try {
            $category = $this->model->get($this->id);
            $this->assertTrue(!empty($category->id) &&
                                                  $this->id === $category->id);
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
            $categories = $this->model->getByProject($this->projectId);
            $this->assertGreaterThan(0, count($categories));
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    public function provider()
    {
        return [
            [
              [
                'name'   => 'Test category',
                'parent' => 0
              ]
            ]
        ];
    }
}
