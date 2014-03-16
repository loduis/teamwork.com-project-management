<?php

class Comment_FileTest extends TestCase
{
    private $model;
    private $resourceId;
    private $projectId;
    private $id;

    public function setUp()
    {
        parent::setUp();
        $this->model      = TeamWorkPm\Factory::build('comment/file');
        $this->projectId  = get_first_project_id();
        $this->resourceId = get_first_file_id($this->projectId);
        $this->id         = get_first_file_comment_id($this->resourceId);
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
            $this->assertEquals('Required field resource_id', $e->getMessage());
        }
        try {
            $data['files'] = dirname(__DIR__) . '/uploads/teamworkpm.jpg';
            $file                = TeamWorkPm\Factory::build('file');
            $file                = $file->get($this->resourceId);
            $data['resource_id'] = $file->versionId;
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
            $data['id'] = $this->id;
            $this->assertTrue($this->model->save($data));
        } catch (\TeamWorkPm\Exception $e) {
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
            $comment = $this->model->get($this->id);
            $this->assertTrue(!empty($comment->id) &&
                                                  $this->id === $comment->id);
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @depends insert
     * @test
     */
    public function getRecent()
    {
        try {
            $times = $this->model->getRecent(0);
            $this->fail('An expected exception has not been raised.');
        } catch (Exception $e) {
            $this->assertEquals('Invalid param resource_id', $e->getMessage());
        }
        try {
            $comments = $this->model->getRecent($this->resourceId);
            $this->assertGreaterThan(0, count($comments));
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }


    public function provider()
    {
        return [
            [
              [
                'body'   => 'Comment, Comment, Blaa',
                'notify' => false,
                'isprivate'=> false,
                'pending_file_attachments'=> null
              ]
            ]
        ];
    }
}
