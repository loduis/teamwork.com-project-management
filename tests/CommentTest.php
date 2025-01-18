<?php

namespace TeamWorkPm\Tests;

use TeamWorkPm\Tests\TestCase;

final class CommentTest extends TestCase
{
    /**
     * @dataProvider provider
     * @test
     */
    public function update($data): void
    {
        $data['id'] = TPM_TEST_ID;
        $this->assertTrue($this->factory('comment', [
            'PUT /comments/' . TPM_TEST_ID => true
        ])->save($data));
    }

    /**
     * @test
     */
    public function delete(): void
    {
        $this->assertTrue($this->factory('comment', [
            'DELETE /comments/' . TPM_TEST_ID => true
        ])->delete(TPM_TEST_ID));
    }

    /**
     * @test
     */
    public function markAsRead(): void
    {
        $this->assertTrue($this->factory('comment', [
            'PUT /comments/' . TPM_TEST_ID . '/markread' => true
        ])->markAsRead(TPM_TEST_ID));
    }

    public function testReact(): void
    {
        $this->assertTrue($this->factory('comment', [
            'PUT /comments/' . TPM_COMMENT_ID . '/react' => true
        ])->react(TPM_TEST_ID));
    }

    public function testUnReact(): void
    {
        $this->assertTrue($this->factory('comment', [
            'PUT /comments/' . TPM_COMMENT_ID . '/unreact' => true
        ])->unReact(TPM_TEST_ID));
    }

    /**
     * @test
     */
    public function getAll(): void
    {
        $this->assertGreaterThan(1, count($this->factory('comment', [
            'GET /comments' => true
        ])->all()));
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
