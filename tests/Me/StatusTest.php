<?php

namespace TeamWorkPm\Tests\Me;

use TeamWorkPm\Exception;
use TeamWorkPm\Factory;
use TeamWorkPm\Tests\TestCase;

final class StatusTest extends TestCase
{
    private $model;
    private static $id;

    protected function setUp(): void
    {
        parent::setUp();
        $this->model = Factory::build('me/status');
    }

    /**
     * @dataProvider provider
     * @test
     */
    public function insert($data): void
    {
        try {
            self::$id = $this->model->save($data);
            $this->assertGreaterThan(0, self::$id);
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
            $status = $this->model->get();
            $this->assertEquals($status->id, self::$id);
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
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
            $data['status'] = rand_string($data['status']);
            $this->assertTrue($this->model->save($data));
            $status = $this->model->get();
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
                    'status' => 'Test me status',
                    'notify' => false,
                ],
            ],
        ];
    }
}
