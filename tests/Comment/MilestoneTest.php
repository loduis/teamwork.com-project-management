<?php

class Comment_MilestoneTest extends TestCase
{
    private $model;
    private $resourceId;
    private $id;

    public function setUp()
    {
        parent::setUp();
        $this->model      = TeamWorkPm\Factory::build('comment/milestone');
        $project_id       = get_first_project_id();
        $this->resourceId = get_first_milestone_id($project_id);
        $this->id         = get_first_milestone_comment_id($this->resourceId);
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
            $data['resource_id'] = $this->resourceId;
            $id = $this->model->save($data);
            $this->assertGreaterThan(0, $id);
        } catch (\TeamWorkPm\Exception $e) {
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
            $data['id'] = $this->id;
            $this->assertTrue($this->model->save($data));
        } catch (\TeamWorkPm\Exception $e) {
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
            $comment = $this->model->get($this->id);
            $this->assertTrue(!empty($comment->id) &&
                                                  $this->id === $comment->id);
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     *
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
