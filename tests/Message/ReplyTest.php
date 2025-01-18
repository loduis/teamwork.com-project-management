<?php

namespace TeamWorkPm\Tests\Message;

use TeamWorkPm\Tests\TestCase;

final class ReplyTest extends TestCase
{
    /**
     * @dataProvider provider
     */
    public function testCreate(array $data): void
    {
        $data['message_id'] = TPM_MESSAGE_ID;

        $this->assertEquals(TPM_TEST_ID, $this->factory('message.reply', [
            'POST /messages/' . TPM_MESSAGE_ID .'/messageReplies' => fn($data) => $this->assertMatchesJsonSnapshot($data)
        ])->create($data));
    }

    public function testGetByMessage(): void
    {
        $this->assertGreaterThan(0, count($this->factory('message.reply', [
            'GET /messages/' . TPM_MESSAGE_ID . '/replies' => true
        ])->getByMessage(TPM_MESSAGE_ID)));
    }

    public function testGet(): void
    {
        $this->assertEquals(
            'test',
            $this->factory('message.reply', [
                'GET /messageReplies/' . TPM_MESSAGE_REPLY_ID => true
            ])->get(TPM_MESSAGE_REPLY_ID)->body
        );
    }

    public function testReact(): void
    {
        $this->assertTrue($this->factory('message.reply', [
            'PUT /messageReplies/' . TPM_MESSAGE_REPLY_ID . '/react' => true
        ])->react(TPM_TEST_ID));
    }

    public function testUnReact(): void
    {
        $this->assertTrue($this->factory('message.reply', [
            'PUT /messageReplies/' . TPM_MESSAGE_REPLY_ID . '/unreact' => true
        ])->unReact(TPM_TEST_ID));
    }


    /**
     * @dataProvider provider
     */
    public function testUpdate(array $data): void
    {
        $data['id'] = TPM_MESSAGE_REPLY_ID;
        $data['body'] = 'Reply message updated';
        $this->assertTrue($this->factory('message.reply', [
            'PUT /messageReplies/' . TPM_MESSAGE_REPLY_ID => true
        ])->save($data));
    }

    public function testDelete(): void
    {
        $this->assertTrue($this->factory('message.reply', [
            'DELETE /messageReplies/' . TPM_MESSAGE_REPLY_ID => true
        ])->delete(TPM_MESSAGE_REPLY_ID));
    }

    public function provider()
    {
        return [
            [
                [
                    'body' => 'Reply message',
                    'notify' => 'ALL',
                ],
            ],
        ];
    }
}
