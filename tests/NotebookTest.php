<?php

namespace TeamWorkPm\Tests;

use TeamWorkPm\Exception;
use TeamWorkPm\Factory;

final class NotebookTest extends TestCase
{
    private $model;
    private $id;
    private $projectId;

    protected function setUp(): void
    {
        parent::setUp();
        $this->model = Factory::build('notebook');
        $this->projectId = get_first_project_id();
        $this->id = get_first_notebook_id($this->projectId);
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
            $this->fail($e->getMessage());
        }
    }

    /**
     * @test
     */
    public function get(): void
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
            $notebooks = $this->model->getAll();
            $this->assertGreaterThan(0, count($notebooks));
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }

        // get with content
    }

    /**
     * @test
     */
    public function getByProject(): void
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
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
        // get with content
    }

    /**
     * @test
     */
    public function lock(): void
    {
        try {
            $this->model->lock(0);
            $this->fail('An expected exception has not been raised.');
        } catch (Exception $e) {
            $this->assertEquals('Invalid param id', $e->getMessage());
        }

        try {
            $this->assertTrue($this->model->lock($this->id));
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @test
     */
    public function unlook(): void
    {
        try {
            $this->model->unlock(0);
            $this->fail('An expected exception has not been raised.');
        } catch (Exception $e) {
            $this->assertEquals('Invalid param id', $e->getMessage());
        }

        try {
            $this->assertTrue($this->model->unlock($this->id));
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    public function provider()
    {
        return [
            [
                [
                    'name' => 'Test notebook',
                    'description' => 'Bla, Bla, Bla',
                    'content' => '<b>Nada</b>, <i>nada</i>, nada',
                    'notify' => false,
                    'category_id' => 0,
                    'category_name' => 'New Notebook category.',
                    'private' => false,
                ],
            ],
        ];
    }
}
