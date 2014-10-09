<?php

class Message_ReplyTest extends TestCase
{

    private $model;
    private static $id;
    private $messageId;

    public function setUp()
    {
        parent::setUp();
        $this->model = TeamWorkPm\Factory::build('message/reply');
        $project_id = get_first_project_id();
        $this->messageId = get_first_message_id($project_id);
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
        } catch(\TeamWorkPm\Exception $e) {
            $this->assertEquals('Required field message_id', $e->getMessage());
        }
        try {
            $_data = [
                'message_id'=> 10
            ];
            $this->model->save($_data);
            $this->fail('An expected exception has not been raised.');
        } catch(\TeamWorkPm\Exception $e) {
            $this->assertEquals('Required field body', $e->getMessage());
        }
        try {
            $data['message_id'] = $this->messageId;
            self::$id   = $this->model->save($data);
            $this->assertGreaterThan(0, self::$id);
        } catch(\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     *
     * @test
     */
    public function getByMessage()
    {
        try {
            $this->model->getByMessage(0);
            $this->fail('An expected exception has not been raised.');
        } catch (Exception $e) {
            $this->assertEquals('Invalid param message_id', $e->getMessage());
        }
        try {
            $replies = $this->model->getByMessage($this->messageId, [
                'pageSize'=> 10,
                'invalid_param' => true
            ]);
            $this->assertGreaterThan(0, count($replies));
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
            $reply = $this->model->get(self::$id);
            $this->assertEquals($reply->id, self::$id);
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
            $data['body'] = rand_string($data['body']);
            $this->assertTrue($this->model->save($data));
            $reply = $this->model->get(self::$id);
            $this->assertEquals($data['body'], $reply->body);
        } catch(\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    public function provider()
    {
        return [
            [
              [
                'body' => 'Reply message',
                'notify'   => false
              ]
            ]
        ];
    }
}
