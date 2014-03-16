<?php

class Me_StatusTest extends TestCase
{

    private $model;
    private static $id;

    public function setUp()
    {
        parent::setUp();
        $this->model = TeamWorkPm\Factory::build('me/status');
    }

    /**
     * @dataProvider provider
     * @test
     */
    public function insert($data)
    {
        try {
            self::$id = $this->model->save($data);
            $this->assertGreaterThan(0, self::$id);
        } catch(\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     *
     * @test
     */
    public function get()
    {
        try {
            $status = $this->model->get();
            $this->assertEquals($status->id, self::$id);
        } catch(\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @dataProvider provider
     * @test
     */
    public function update($data)
    {
        try {
            $data['id'] = null;
            $this->model->save($data);
            $this->fail('An expected exception has not been raised.');
        } catch (\TeamWorkPm\Exception $e) {
            $this->assertEquals('Required field id', $e->getMessage());
        }
        try {
            $data['id']     = self::$id;
            $data['status'] = rand_string($data['status']);
            $this->assertTrue($this->model->save($data));
            $status = $this->model->get();
            $this->assertEquals($data['status'], $status->status);
        } catch(\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    public function provider()
    {
        return [
            [
              [
                'status' => 'Test me status',
                'notify'   => false
              ]
            ]
        ];
    }
}
