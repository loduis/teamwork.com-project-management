<?php

namespace TeamWorkPm\Tests\Comment;

use TeamWorkPm\Exception;
use TeamWorkPm\Tests\TestCase;

final class MilestoneTest extends TestCase
{
    /**
     * @dataProvider provider
     * @test
     */
    public function insert($data): void
    {
        try {
            $this->factory('comment.milestone')->save($data);
            $this->fail('An expected exception has not been raised.');
        } catch (Exception $e) {
            $this->assertEquals('Required field resource_id', $e->getMessage());
        }

        $data['files'] = dirname(__DIR__) . '/uploads/teamworkpm.jpg';
        $data['resource_id'] = TPM_MILESTONE_ID;
        $this->assertEquals(TPM_TEST_ID, $this->factory('comment.milestone', [
            'POST /pendingfiles' => function () {
                return '{"pendingFile":{"ref":"tf_3d9cfae3-65f7-4ff8-8bf5-ca0512de600a"}}';
            },
            'POST /milestones/' . TPM_MILESTONE_ID . '/comments' => true
        ])->create($data));
    }

    /**
     * @depends insert
     * @test
     */
    public function get(): void
    {
        $this->assertEquals(
            'milestone',
            $this->factory('comment.milestone')->get(25845937)->type
        );
    }

    /**
     * @depends insert
     * @test
     */
    public function getRecent(): void
    {
        $this->assertGreaterThan(0, count($this->factory('comment.milestone', [
            'GET /milestones/' . TPM_MILESTONE_ID . '/comments' => true
        ])->getRecent(TPM_MILESTONE_ID)));
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
