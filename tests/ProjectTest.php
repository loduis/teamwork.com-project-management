<?php

namespace TeamWorkPm\Tests;

use TeamWorkPm\Exception;
use TeamWorkPm\Factory;

class ProjectTest extends TestCase
{
    private $id;

    private $model;

    public function setUp(): void
    {
        parent::setUp();
        $this->model = Factory::build('project');
        $this->id = get_first_project_id();
    }

    /**
     * @dataProvider provider
     * @test
     */
    public function insert($data): void
    {
        try {
            $data['category_id'] = get_first_project_category_id();
            $id = $this->model->save($data);
            $this->assertGreaterThan(0, $id);
            $project = $this->model->get($id);
            $this->assertEquals((int)$project->category->id, $data['category_id']);
        } catch (Exception $e) {
            $this->assertEquals('Project name taken', $e->getMessage());
        }
    }

    /**
     * @dataProvider provider
     * @test
     */
    public function update($data): void
    {
        try {
            $data['id'] = $this->id;
            $data['category_id'] = 0;
            $this->assertTrue($this->model->save($data));
            $project = $this->model->get($this->id);
            $this->assertEquals((int)$project->category->id, $data['category_id']);
        } catch (Exception $e) {
            $this->assertEquals('Project name taken', $e->getMessage());
        }
    }

    /**
     * @dataProvider provider
     * @test
     */
    public function updateWithoutId($data): void
    {
        $this->expectException(\Exception::class);
        $this->model->update($data);
    }

    /**
     * @test
     */
    public function star(): void
    {
        try {
            $this->assertTrue($this->model->star($this->id));
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @test
     */
    public function starInvalidProjectId(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid param id');
        $this->model->star(0);
    }

    /**
     * @depends star
     * @test
     */
    public function getStarred(): void
    {
        try {
            $projects = $this->model->getStarred();
            $this->assertGreaterThan(0, count($projects));
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @test
     */
    public function unStarInvalidProjectId(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid param id');
        $this->model->unStar(0);
    }

    /**
     * @test
     */
    public function unStar(): void
    {
        try {
            $this->assertTrue($this->model->unStar($this->id));
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @test
     */
    public function archiveInvalidProjectId(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid param id');
        $this->model->archive(0);
    }

    /**
     * @test
     */
    public function archive(): void
    {
        try {
            $this->assertTrue($this->model->archive($this->id));
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @depends archive
     * @test
     */
    public function getArchived(): void
    {
        try {
            $projects = $this->model->getArchived();
            $this->assertGreaterThan(0, count($projects));
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @test
     */
    public function activateInvalidProjectId(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid param id');
        $this->model->activate(0);
    }

    /**
     * @test
     */
    public function activate(): void
    {
        try {
            $this->assertTrue($this->model->activate($this->id));
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @depends activate
     * @test
     */
    public function getActive(): void
    {
        try {
            $projects = $this->model->getActive();
            $this->assertGreaterThan(0, count($projects));
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @test
     */
    public function get(): void
    {
        try {
            $project = $this->model->get($this->id);
            $this->assertEquals($project->id, $this->id);
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @test
     */
    public function getAll(): void
    {
        try {
            $projects = $this->model->getAll();
            $this->assertGreaterThan(0, count($projects));
            $save = $projects->save(__DIR__ . '/build/projects');
            $this->assertTrue(is_numeric($save));
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @test
     */
    public function deleteInvalidId(): void
    {
        $this->expectException(\Exception::class);
        $this->model->delete(0);
    }

    public function provider()
    {
        return [
            [
                [
                    'name' => 'test project',
                    'description' => 'bla, bla, bla',
                    'start_date' => 20121110,
                    'end_date' => 20121210,
                    'new_company' => 'Test Company',
                ],
            ],
        ];
    }
}
