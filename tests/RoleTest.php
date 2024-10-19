<?php

namespace TeamWorkPm\Tests;

use Exception;
use TeamWorkPm\Factory;

final class RoleTest extends TestCase
{
    private $model;
    private static $id;

    protected function setUp(): void
    {
        parent::setUp();
        $this->model = Factory::build('role');
    }

    /**
     * @dataProvider provider
     * @test
     */
    public function insert($data): void
    {
        // =========== insert now ========= //
        try {
            self::$id = $this->model->insert($data);
            $this->assertGreaterThan(0, self::$id);
        } catch (\TeamWorkPm\Exception $e) {
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
            $data['id'] = null;
            $this->assertTrue($this->model->update($data));
        } catch (\TeamWorkPm\Exception $e) {
            $this->assertEquals('Required field id', $e->getMessage());
        }
        try {
            // and add to this project
            $data['id'] = self::$id;
            $this->assertTrue($this->model->update($data));
        } catch (\TeamWorkPm\Exception $e) {
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
            $this->model->get(0);
            $this->fail('An expected exception has not been raised.');
        } catch (Exception $e) {
            $this->assertEquals('Invalid param id', $e->getMessage());
        }
        try {
            $tag = $this->model->get(self::$id);
            $this->assertEquals(self::$id, $tag->id);
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
        // get roles in project
        try {
            $project_id = get_first_project_id();
            $roles = $this->model->getByProject($project_id);

            $this->assertGreaterThan(0, count($roles));
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    public function provider()
    {
        $project_id = get_first_project_id('active');
        return [
            [
                [
                    'name' => 'Test Role',
                    'description' => 'Test role for testing unit tests',
                    'project_id' => $project_id,
                    'users' => get_first_person_id($project_id),
                ],
            ],
        ];
    }
}
