<?php

namespace TeamWorkPm\Tests;

final class MessageTest extends TestCase
{

    /**
     * @dataProvider provider
     */
    public function testCreate(array $data): void
    {
        $data['project_id'] = TPM_PROJECT_ID_1;
        $this->assertEquals(TPM_TEST_ID, $this->factory('message', [
            'POST /projects/' . TPM_PROJECT_ID_1 . '/posts' => fn($data) => $this->assertMatchesJsonSnapshot($data)
        ])->create($data));
    }

    public function testAll(): void
    {
        $this->assertGreaterThan(0, count($this->factory('message', [
            'GET /posts' => true
        ])->all()));
    }

    public function testGet(): void
    {
        $this->assertEquals('b', $this->factory('message', [
            'GET /posts/' . TPM_MESSAGE_ID => true
        ])->get(TPM_MESSAGE_ID)->title);
    }

    /**
     * @dataProvider provider
     */
    public function testUpdate(array $data): void
    {
        $data['title'] = 'Test message updated';
        $data['id'] = TPM_MESSAGE_ID;

        $this->assertTrue($this->factory('message', [
            'PUT /posts/' . TPM_MESSAGE_ID => fn($data) => $this->assertMatchesJsonSnapshot($data)
        ])->update($data));
    }

    public function testGetByProject(): void
    {
        $this->assertGreaterThan(0, count($this->factory('message', [
            'GET /projects/' . TPM_PROJECT_ID_1 . '/posts' => true
        ])->getByProject(TPM_PROJECT_ID_1)));
    }

    public function testArchive(): void
    {
        $this->assertTrue($this->factory('message', [
            'PUT /messages/' . TPM_MESSAGE_ID . '/archive' => true
        ])->archive(TPM_MESSAGE_ID));
    }

    public function testGetByProjectArchived(): void
    {
        $this->assertGreaterThan(0, count($this->factory('message', [
            'GET /projects/' . TPM_PROJECT_ID_1 . '/posts/archive' => true
        ])->getByProject(TPM_PROJECT_ID_1, true)));
    }

    public function testUnArchive(): void
    {
        $this->assertTrue($this->factory('message', [
            'PUT /messages/' . TPM_MESSAGE_ID . '/unarchive' => true
        ])->unArchive(TPM_MESSAGE_ID));
    }

    public function testGetByProjectAndCategory(): void
    {
        $this->assertGreaterThan(0, count($this->factory('message', [
            'GET /projects/' . TPM_PROJECT_ID_1 . '/cat/' . TPM_MESSAGE_CATEGORY_ID . '/posts' => true
        ])->getByProjectAndCategory(TPM_PROJECT_ID_1, TPM_MESSAGE_CATEGORY_ID)));
    }

    public function testGetByProjectAndCategoryArchived(): void
    {
        $this->assertGreaterThan(0, count($this->factory('message', [
            'GET /projects/' . TPM_PROJECT_ID_1 . '/cat/' . TPM_MESSAGE_CATEGORY_ID . '/posts/archive' => true
        ])->getByProjectAndCategory(TPM_PROJECT_ID_1, TPM_MESSAGE_CATEGORY_ID, true)));
    }

    public function testMarkAsRead(): void
    {
        $this->assertTrue($this->factory('message', [
            'PUT /messages/' . TPM_MESSAGE_ID . '/markread' => true
        ])->markAsRead(TPM_MESSAGE_ID));
    }

    public function testDelete(): void
    {
        $this->assertTrue($this->factory('message', [
            'DELETE /posts/' . TPM_MESSAGE_ID => true
        ])->delete(TPM_MESSAGE_ID));
    }

    public function provider()
    {
        return [
            [
                [
                    'title' => 'Test message',
                    'body' => '<b>Nada</b>, <i>nada</i>, nada',
                    // 'notify' => false,
                    'private' => false,
                    'category_id' => 0,
                    'attachments' => null,
                    'pending_file_attachments' => null,
                ],
            ],
        ];
    }
}
