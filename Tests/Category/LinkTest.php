<?php

class Category_LinkTest extends TestCase
{
    private $model;
    private $projectId;
    private $id;

    public function setUp()
    {
        parent::setUp();
        $this->model     = TeamWorkPm::factory('category/link');
        $this->projectId = get_first_project_id();
        $this->id        = get_first_link_category_id($this->projectId);
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
            $this->assertEquals('Required field project_id', $e->getMessage());
        }
        try {
          $data['project_id']  = $this->projectId;
          $id = $this->model->save($data);
          $this->assertGreaterThan(0, $id);
        } catch (\TeamWorkPm\Exception $e) {
            $code = $e->getCode();
            switch ($code) {
              case \TeamWorkPm\Error::ALREADY_EXISTS:
                $this->markTestSkipped($e->getMessage());
                break;
              default:
                $this->assertTrue(false, $e->getMessage());
                break;
            }
        }
    }

    /**
     * @dataProvider provider
     * @test
     */
    public function update($data)
    {
        try {
            $data['id'] = $this->id;
            $this->assertTrue($this->model->save($data));
        } catch (\TeamWorkPm\Exception $e) {
            $this->assertTrue(false, $e->getMessage());
        }
    }

    /**
     *
     * @test
     */
    public function get()
    {
        try {
            $category = $this->model->get($this->id);
            $this->assertTrue(!empty($category->id) &&
                                                  $this->id === $category->id);
        } catch (\TeamWorkPm\Exception $e) {
            $this->assertTrue(false, $e->getMessage());
        }
    }

    /**
     *
     * @test
     */
    public function getByProject()
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
        } catch (\TeamWorkPm\Exception $e) {
            $this->assertTrue(false, $e->getMessage());
        }
    }


    public function provider()
    {
        return array(
            array(
              array(
                'name'   => 'Test category',
                'parent' => 0
              )
            )
        );
    }
}
