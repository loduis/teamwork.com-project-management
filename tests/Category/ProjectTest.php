<?php

namespace TeamWorkPm\Tests\Category;

use TeamWorkPm\Exception;
use TeamWorkPm\Factory;
use TeamWorkPm\Tests\TestCase;

class ProjectTest extends TestCase
{
    private $model;
    private $id;

    public function setUp()
    {
        parent::setUp();
        $this->model = Factory::build('category/project');
        $this->id = get_first_project_category_id();
    }

    /**
     * @dataProvider provider
     * @test
     */
    public function insert($data)
    {
        try {
            $id = $this->model->save($data);
            $this->assertGreaterThan(0, $id);
        } catch (Exception $e) {
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
        } catch (Exception $e) {
            $this->assertEquals('Already exists', $e->getMessage());
        }
    }

    /**
     * @test
     */
    public function get()
    {
        try {
            $category = $this->model->get($this->id);
            $this->assertTrue(!empty($category->id) && $this->id === $category->id);
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @test
     */
    public function getAll()
    {
        try {
            $categories = $this->model->getAll();
            $this->assertGreaterThan(0, count($categories));
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    public function provider()
    {
        return [
            [
                [
                    'name' => 'Test category',
                    'parent' => 0,
                ],
            ],
        ];
    }
}
