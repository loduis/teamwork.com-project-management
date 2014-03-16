<?php

class MessageTest extends TestCase
{
    private $model;
    private $id;
    private $projectId;

    public function setUp()
    {
        parent::setUp();
        $this->model     = TeamWorkPm\Factory::build('message');
        $this->projectId = get_first_project_id();
        $this->id        = get_first_message_id($this->projectId);
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
            $data['project_id'] = $this->projectId;
            // upload file to the server
            $data['files'] = [
                __DIR__ . '/uploads/teamworkpm.jpg',
                __DIR__ . '/uploads/person.png'
            ];
            $id = $this->model->save($data);
            $this->assertGreaterThan(0, $id);
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
            $data['id']          = $this->id;
            $data['category_id'] = get_first_message_category_id($this->projectId);
            $this->assertTrue($this->model->save($data));
        } catch (\TeamWorkPm\Exception $e) {
            $this->assertEquals('Not method supported', $e->getMessage());
        }
    }

    /**
     * @depends insert
     * @test
     */
    public function get()
    {
        try {
            $this->model->get(0);
            $this->fail('An expected exception has not been raised.');
        } catch (Exception $e) {
            $this->assertEquals('Invalid param id', $e->getMessage());
        }
        try {
            $message = $this->model->get($this->id);
            $this->assertEquals($this->id, $message->id);
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }


    /**
     * @depends insert
     * @test
     */
    public function getByProject()
    {
        try {
            $this->model->getByProject(0);
            $this->fail('An expected exception has not been raised.');
        } catch (Exception $e) {
            $this->assertEquals('Invalid param project_id', $e->getMessage());
        }
        try {
            $messages = $this->model->getByProject($this->projectId);
            //print_r($messages);
            $this->assertGreaterThan(0, count($messages));
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
        // get archive
        try {
            $messages = $this->model->getByProject($this->projectId, true);
            $this->assertCount(0, $messages);
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
        // get with content

    }

    /**
     * @depends insert
     * @test
     */
    public function getByProjectAndCategory()
    {
        try {
            $this->model->getByProjectAndCategory(0, 0);
            $this->fail('An expected exception has not been raised.');
        } catch (Exception $e) {
            $this->assertEquals('Invalid param project_id', $e->getMessage());
        }
        try {
            $this->model->getByProjectAndCategory(1, 0);
            $this->fail('An expected exception has not been raised.');
        } catch (Exception $e) {
            $this->assertEquals('Invalid param category_id', $e->getMessage());
        }
        try {
            $category_id = get_first_message_category_id($this->projectId);
            $messages = $this->model->getByProjectAndCategory(
                $this->projectId,
                $category_id
            );
            $this->assertGreaterThan(0, count($messages));
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
        // get archive
        try {
            $messages = $this->model->getByProjectAndCategory(
                $this->projectId,
                $category_id,
                true
            );
            $this->assertCount(0, $messages);
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    public function provider()
    {
        return [
            [
              [
                'title'          => 'Test message',
                'body'       => '<b>Nada</b>, <i>nada</i>, nada',
                'notify'        => false,
                'private'       => false,
                'category_id'   => 0,
                'attachments'   => null,
                'pending_file_attachments' => null
              ]
            ]
        ];
    }
}
