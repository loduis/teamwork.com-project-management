<?php

namespace TeamWorkPm\Tests\Category;

use TeamWorkPm\Category\File;
use TeamWorkPm\Exception;
use TeamWorkPm\Factory;
use TeamWorkPm\Tests\TestCase;

final class FileTest extends TestCase
{
    /**
     * @var File
     */
    private $model;
    private $projectId;
    private $id;

    protected function setUp(): void
    {
        parent::setUp();
        $this->model = Factory::build('category/file');
        $this->projectId = get_first_project_id();
        $this->id = get_first_file_category_id($this->projectId);
    }

    /**
     * @dataProvider provider
     * @test
     */
    public function insert($data): void
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
        } catch (Exception $e) {
            $this->assertEquals('Already exists', $e->getMessage());
        }
    }

    /**
     * @depends      insert
     * @dataProvider provider
     * @test
     */
    public function update($data): void
    {
        try {
            $data['id'] = $this->id;
            $this->assertTrue($this->model->save($data));
        } catch (Exception $e) {
            $this->assertEquals('Already exists', $e->getMessage());
        }
    }

    /**
     * @depends insert
     * @test
     */
    public function get(): void
    {
        try {
            $category = $this->model->get($this->id);
            $this->assertTrue(!empty($category->id) && $this->id === $category->id);
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @depends insert
     * @test
     */
    public function getByProject(): void
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
