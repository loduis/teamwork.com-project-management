<?php

namespace TeamWorkPm\Tests\Comment;

use TeamWorkPm\Exception;
use TeamWorkPm\Tests\TestCase;

final class NotebookTest extends TestCase
{
    /**
     * @dataProvider provider
     * @test
     */
    public function insert($data): void
    {
        try {
            $this->factory('comment.notebook')->save($data);
            $this->fail('An expected exception has not been raised.');
        } catch (Exception $e) {
            $this->assertEquals('Required field resource_id', $e->getMessage());
        }

        $data['files'] = dirname(__DIR__) . '/uploads/teamworkpm.jpg';
        $data['resource_id'] = TPM_NOTEBOOK_ID;
        $this->assertEquals(TPM_TEST_ID, $this->factory('comment.notebook', [
            'POST /pendingfiles' => function () {
                return '{"pendingFile":{"ref":"tf_3d9cfae3-65f7-4ff8-8bf5-ca0512de600a"}}';
            },
            'POST /notebooks/' . TPM_NOTEBOOK_ID . '/comments' => true
        ])->create($data));
    }

    /**
     * @depends insert
     * @test
     */
    public function get(): void
    {
        $this->assertEquals(
            'notebook',
            $this->factory('comment.notebook')->get(25845837)->type
        );
    }

    /**
     * @depends insert
     * @test
     */
    public function getRecent(): void
    {
        $this->assertGreaterThan(0, count($this->factory('comment.notebook', [
            'GET /notebooks/' . TPM_NOTEBOOK_ID . '/comments' => true
        ])->getRecent(TPM_NOTEBOOK_ID)));
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
