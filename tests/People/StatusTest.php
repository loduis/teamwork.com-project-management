<?php

class People_StatusTest extends TestCase
{

    private $model;
    private static $id;
    private static $userId = null;

    public function setUp()
    {
        parent::setUp();
        $this->model = TeamWorkPm\Factory::build('people/status');
        if (!self::$userId) {
            $people = TeamWorkPm\Factory::build('people');
            foreach ($people->getAll() as $p) {
                if (!$p->siteOwner) {
                    self::$userId = (int) $p->id;
                    break;
                }
            }
        }
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
            $this->assertEquals('Required field person_id', $e->getMessage());
        }
        try {
            $data['person_id'] = self::$userId;
            self::$id = $this->model->insert($data);
            $this->assertGreaterThan(0, self::$id);
        } catch(\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @depends insert
     * @test
     */
    public function get()
    {
        try {
            $status = $this->model->get(self::$userId);
            $this->assertEquals($status->id, self::$id);
        } catch(\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @depends insert
     * @test
     */
    public function getAll()
    {
         try {
            $status  = $this->model->getAll();
            $this->assertGreaterThan(0, count($status));
         } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
         }
    }

    /**
     * @depends insert
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
            $data['id']        = self::$id;
            $data['person_id'] = self::$userId;
            $data['status']    = rand_string($data['status']);
            $this->assertTrue($this->model->update($data));
            $status = $this->model->get(self::$userId);
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
                'status' => 'Test people status',
                'notify'   => false
              ]
            ]
        ];
    }
}
