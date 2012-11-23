<?php

class Task_ListTest extends TestCase
{
    private $model;

    public function setUp()
    {
        parent::setUp();
        $this->model = TeamWorkPm::factory('task/list');
    }

    /**
     *
     * @test
     */
    public function insert()
    {
        try {
          $data = load_data('task_list');
          $project_id = self::getFirstProject();
          $data['project_id'] = $project_id;
          $id = $this->model->save($data);
          $this->assertGreaterThan(0, $id);
        } catch (\TeamWorkPm\Exception $e) {
            $code = $e->getCode();
            switch ($code) {
              case \TeamWorkPm\Error::PROJECT_NAME_TAKEN:
                $this->markTestSkipped($e->getMessage());
                break;
              default:
                $this->assertTrue(false, $e->getMessage());
                break;
            }
        }
    }
}