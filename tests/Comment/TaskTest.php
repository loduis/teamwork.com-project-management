<?php

namespace TeamWorkPm\Tests\Comment;

use TeamWorkPm\Exception;
use TeamWorkPm\Tests\TestCase;

final class TaskTest extends TestCase
{
    /**
     * @dataProvider provider
     * @test
     */
    public function insert($data): void
    {
        try {
            $this->factory('comment.task')->save($data);
            $this->fail('An expected exception has not been raised.');
        } catch (Exception $e) {
            $this->assertEquals('Required field resource_id', $e->getMessage());
        }

        $data['files'] = dirname(__DIR__) . '/uploads/teamworkpm.jpg';
        $data['resource_id'] = TPM_TASK_ID;
        $this->assertEquals(TPM_TEST_ID, $this->factory('comment.task', [
            'POST /pendingfiles' => function () {
                return '{"pendingFile":{"ref":"tf_3d9cfae3-65f7-4ff8-8bf5-ca0512de600a"}}';
            },
            'POST /tasks/' . TPM_TASK_ID . '/comments' => true
        ])->create($data));
    }

    /**
     * @depends insert
     * @test
     */
    public function get(): void
    {
        $this->assertEquals(
            'task',
            $this->factory('comment.task')->get(25845823)->type
        );
    }

    /**
     * @depends insert
     * @test
     */
    public function getRecent(): void
    {
        $this->assertGreaterThan(1, count($this->factory('comment.task')->getRecent(TPM_TASK_ID)));
    }

    public function provider()
    {
        return [
            [
                [
                    'body' => 'Comment, Comment, Blaa',
                    'notify' => false,
                    'isprivate' => false,
                    'pending_file_attachments' => null,
                ],
            ],
        ];
    }
}
