<?php

namespace TeamWorkPm\Tests;

use TeamWorkPm\Exception;
use TeamWorkPm\Factory;

final class LinkTest extends TestCase
{
    private $model;
    private $id;
    private $projectId;

    protected function setUp(): void
    {
        parent::setUp();
        $this->model = Factory::build('link');
        $this->projectId = get_first_project_id();
        $this->id = get_first_link_id();
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
            // no required
            $data['category_id'] = get_first_link_category_id($this->projectId);
            $id = $this->model->save($data);
            $this->assertGreaterThan(0, $id);
        } catch (Exception $e) {
            $this->fail($e->getMessage());
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
            $data['category_id'] = 0;
            $this->assertTrue($this->model->save($data));
            $link = $this->model->get($this->id);
            $this->assertEquals((int)$link->categoryId, 0);
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @depends insert
     * @test
     */
    public function get(): void
    {
        try {
            $times = $this->model->get(0);
            $this->fail('An expected exception has not been raised.');
        } catch (Exception $e) {
            $this->assertEquals('Invalid param id', $e->getMessage());
        }
        try {
            $link = $this->model->get($this->id);
            $this->assertEquals($this->id, $link->id);
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
            $links = $this->model->getAll();
            $this->assertGreaterThan(0, count($links));
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
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
            $links = $this->model->getByProject($this->projectId);
            $this->assertGreaterThan(0, count($links));
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    public function provider()
    {
        return [
            [
                [
                    'name' => 'Test Link',
                    'description' => 'Bla, Bla, Bla',
                    'code' => 'http://developer.teamworkpm.net',
                    'height' => 300,
                    'width' => 300,
                    'private' => false,
                    'notify' => null,
                    'open_in_new_window' => true,
                ],
            ],
        ];
    }
}
