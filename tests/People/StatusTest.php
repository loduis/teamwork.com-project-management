<?php

namespace TeamWorkPm\Tests\People;

use TeamWorkPm\Exception;
use TeamWorkPm\Factory;
use TeamWorkPm\Tests\TestCase;

class StatusTest extends TestCase
{
    private $model;
    private static $id;
    private static $userId = null;

    protected function setUp(): void
    {
        parent::setUp();
        $this->model = Factory::build('people/status');
        if (!self::$userId) {
            $people = Factory::build('people');
            foreach ($people->getAll() as $p) {
                if (!$p->siteOwner) {
                    self::$userId = (int)$p->id;
                    break;
                }
            }
        }
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
            $this->assertEquals('Required field person_id', $e->getMessage());
        }
        try {
            $data['person_id'] = self::$userId;
            self::$id = $this->model->insert($data);
            $this->assertGreaterThan(0, self::$id);
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
            $status = $this->model->get(self::$userId);
            $this->assertEquals($status->id, self::$id);
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @depends insert
     * @test
     */
    public function getAll(): void
    {
        try {
            $status = $this->model->getAll();
            $this->assertGreaterThan(0, count($status));
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
            $data['id'] = null;
            $this->model->save($data);
            $this->fail('An expected exception has not been raised.');
        } catch (Exception $e) {
            $this->assertEquals('Required field id', $e->getMessage());
        }

        try {
            $data['id'] = self::$id;
            $data['person_id'] = self::$userId;
            $data['status'] = rand_string($data['status']);
            $this->assertTrue($this->model->update($data));
            $status = $this->model->get(self::$userId);
            $this->assertEquals($data['status'], $status->status);
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    public function provider()
    {
        return [
            [
                [
                    'status' => 'Test people status',
                    'notify' => false,
                ],
            ],
        ];
    }
}
