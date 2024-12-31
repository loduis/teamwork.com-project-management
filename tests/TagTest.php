<?php

namespace TeamWorkPm\Tests;


final class TagTest extends TestCase
{

    /**
     * @dataProvider provider
     */
    public function testCreate(array $data): void
    {
        $this->assertEquals(TPM_TEST_ID, $this->factory('tag', [
            'POST /tags' => fn($data) => $this->assertMatchesJsonSnapshot($data)
        ])->create($data));
    }

    public function testAll(): void
    {
        $this->assertCount(3, $this->factory('tag', [
            'GET /tags' => true
        ])->all());
    }

    public function testGet(): void
    {
        $this->assertEquals('Draft', $this->factory('tag', [
            'GET /tags/' . TPM_TAG_ID => true
        ])->get(TPM_TAG_ID)->name);
    }

    public function testUpdate(): void
    {
        $this->assertTrue($this->factory('tag', [
            'PUT /tags/' . TPM_TAG_ID => true
        ])->update([
            'id' => TPM_TAG_ID,
            'name' => 'Updated Tag Name',
            'color' => '#ae00da',
        ]));
    }

    public function testDelete(): void
    {
        $this->assertTrue($this->factory('tag', [
            'DELETE /tags/' . TPM_TAG_ID => true
        ])->delete(TPM_TAG_ID));
    }

    public function testGetAllFor(): void
    {
        $this->assertGreaterThan(0, count($this->factory('project', [
            'GET /projects/' . TPM_PROJECT_ID_1 . '/tags' => (fn($data) => $this->assertMatchesJsonSnapshot($data))
        ])->getTags(TPM_PROJECT_ID_1)));

        $this->assertGreaterThan(0, count($this->factory('people', [
            'GET /users/' . TPM_USER_ID . '/tags' => (fn($data) => $this->assertMatchesJsonSnapshot($data))
        ])->getTags(TPM_USER_ID)));

        $this->assertGreaterThan(0, count($this->factory('company', [
            'GET /companies/' . TPM_COMPANY_ID . '/tags' => (fn($data) => $this->assertMatchesJsonSnapshot($data))
        ])->getTags(TPM_COMPANY_ID)));

        $this->assertGreaterThan(0, count($this->factory('link', [
            'GET /links/' . TPM_LINK_ID . '/tags' => (fn($data) => $this->assertMatchesJsonSnapshot($data))
        ])->getTags(TPM_LINK_ID)));

        $this->assertGreaterThan(0, count($this->factory('milestone', [
            'GET /milestones/' . TPM_MILESTONE_ID . '/tags' => (fn($data) => $this->assertMatchesJsonSnapshot($data))
        ])->getTags(TPM_MILESTONE_ID)));

        $this->assertGreaterThan(0, count($this->factory('notebook', [
            'GET /notebooks/' . TPM_NOTEBOOK_ID . '/tags' => (fn($data) => $this->assertMatchesJsonSnapshot($data))
        ])->getTags(TPM_NOTEBOOK_ID)));

        $this->assertGreaterThan(0, count($this->factory('task', [
            'GET /tasks/' . TPM_TASK_ID . '/tags' => (fn($data) => $this->assertMatchesJsonSnapshot($data))
        ])->getTags(TPM_TASK_ID)));

        $this->assertGreaterThan(0, count($this->factory('file', [
            'GET /files/' . TPM_FILE_ID . '/tags' => (fn($data) => $this->assertMatchesJsonSnapshot($data))
        ])->getTags(TPM_FILE_ID)));

        $this->assertGreaterThan(0, count($this->factory('time', [
            'GET /timelogs/' . TPM_TIME_ID_1 . '/tags' => (fn($data) => $this->assertMatchesJsonSnapshot($data))
        ])->getTags(TPM_TIME_ID_1)));
    }

    public function testAddTo(): void
    {
        $this->assertTrue($this->factory('project', [
            'PUT /projects/' . TPM_PROJECT_ID_1 . '/tags' => (fn($data) => $this->assertMatchesJsonSnapshot($data))
        ])->addTag(TPM_PROJECT_ID_1, TPM_TAG_ID));

        $this->assertTrue($this->factory('people', [
            'PUT /users/' . TPM_USER_ID . '/tags' => (fn($data) => $this->assertMatchesJsonSnapshot($data))
        ])->addTag(TPM_USER_ID, TPM_TAG_ID));

        $this->assertTrue($this->factory('company', [
            'PUT /companies/' . TPM_COMPANY_ID . '/tags' => (fn($data) => $this->assertMatchesJsonSnapshot($data))
        ])->addTag(TPM_COMPANY_ID, TPM_TAG_ID));

        $this->assertTrue($this->factory('link', [
            'PUT /links/' . TPM_LINK_ID . '/tags' => (fn($data) => $this->assertMatchesJsonSnapshot($data))
        ])->addTag(TPM_LINK_ID, TPM_TAG_ID));

        $this->assertTrue($this->factory('milestone', [
            'PUT /milestones/' . TPM_MILESTONE_ID . '/tags' => (fn($data) => $this->assertMatchesJsonSnapshot($data))
        ])->addTag(TPM_MILESTONE_ID, TPM_TAG_ID));

        $this->assertTrue($this->factory('notebook', [
            'PUT /notebooks/' . TPM_NOTEBOOK_ID . '/tags' => (fn($data) => $this->assertMatchesJsonSnapshot($data))
        ])->addTag(TPM_NOTEBOOK_ID, TPM_TAG_ID));

        $this->assertTrue($this->factory('task', [
            'PUT /tasks/' . TPM_TASK_ID . '/tags' => (fn($data) => $this->assertMatchesJsonSnapshot($data))
        ])->addTag(TPM_TASK_ID, TPM_TAG_ID));

        $this->assertTrue($this->factory('file', [
            'PUT /files/' . TPM_FILE_ID . '/tags' => (fn($data) => $this->assertMatchesJsonSnapshot($data))
        ])->addTag(TPM_FILE_ID, TPM_TAG_ID));

        $this->assertTrue($this->factory('time', [
            'PUT /timelogs/' . TPM_TIME_ID_1 . '/tags' => (fn($data) => $this->assertMatchesJsonSnapshot($data))
        ])->addTag(TPM_TIME_ID_1, TPM_TAG_ID));
    }

    public function testRemoveTo(): void
    {
        $this->assertTrue($this->factory('project', [
            'PUT /projects/' . TPM_PROJECT_ID_1 . '/tags' => (fn($data) => $this->assertMatchesJsonSnapshot($data))
        ])->removeTag(TPM_PROJECT_ID_1, TPM_TAG_ID));

        $this->assertTrue($this->factory('people', [
            'PUT /users/' . TPM_USER_ID . '/tags' => (fn($data) => $this->assertMatchesJsonSnapshot($data))
        ])->removeTag(TPM_USER_ID, TPM_TAG_ID));

        $this->assertTrue($this->factory('company', [
            'PUT /companies/' . TPM_COMPANY_ID . '/tags' => (fn($data) => $this->assertMatchesJsonSnapshot($data))
        ])->removeTag(TPM_COMPANY_ID, TPM_TAG_ID));

        $this->assertTrue($this->factory('link', [
            'PUT /links/' . TPM_LINK_ID . '/tags' => (fn($data) => $this->assertMatchesJsonSnapshot($data))
        ])->removeTag(TPM_LINK_ID, TPM_TAG_ID));

        $this->assertTrue($this->factory('milestone', [
            'PUT /milestones/' . TPM_MILESTONE_ID . '/tags' => (fn($data) => $this->assertMatchesJsonSnapshot($data))
        ])->removeTag(TPM_MILESTONE_ID, TPM_TAG_ID));

        $this->assertTrue($this->factory('notebook', [
            'PUT /notebooks/' . TPM_NOTEBOOK_ID . '/tags' => (fn($data) => $this->assertMatchesJsonSnapshot($data))
        ])->removeTag(TPM_NOTEBOOK_ID, TPM_TAG_ID));

        $this->assertTrue($this->factory('task', [
            'PUT /tasks/' . TPM_TASK_ID . '/tags' => (fn($data) => $this->assertMatchesJsonSnapshot($data))
        ])->removeTag(TPM_TASK_ID, TPM_TAG_ID));

        $this->assertTrue($this->factory('file', [
            'PUT /files/' . TPM_FILE_ID . '/tags' => (fn($data) => $this->assertMatchesJsonSnapshot($data))
        ])->removeTag(TPM_FILE_ID, TPM_TAG_ID));

        $this->assertTrue($this->factory('time', [
            'PUT /timelogs/' . TPM_TIME_ID_1 . '/tags' => (fn($data) => $this->assertMatchesJsonSnapshot($data))
        ])->removeTag(TPM_TIME_ID_1, TPM_TAG_ID));
    }

    public function provider()
    {
        return [
            [
                [
                    'name' => 'Test Tag',
                    'color' => '#ae00da',
                ],
            ],
        ];
    }
}
