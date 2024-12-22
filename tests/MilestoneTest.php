<?php

namespace TeamWorkPm\Tests;


final class MilestoneTest extends TestCase
{
    /**
     * @dataProvider provider
     */
    public function testCreate(array $data): void
    {
        $data['project_id'] = TPM_PROJECT_ID_1;
        $this->assertEquals(TPM_TEST_ID, $this->factory('milestone', [
            'POST /projects/' . TPM_PROJECT_ID_1 . '/milestones' => fn($data) => $this->assertMatchesJsonSnapshot($data)
        ])->create($data));
    }

    public function testAll(): void
    {
        $this->assertGreaterThan(0, count($this->factory('milestone', [
            'GET /milestones' => true
        ])->all()));
    }

    public function testGetByProject(): void
    {
        $this->assertGreaterThan(0, count($this->factory('milestone', [
            'GET /projects/' . TPM_PROJECT_ID_1 . '/milestones' => true
        ])->getByProject(TPM_PROJECT_ID_1)));
    }

    public function testGet(): void
    {
        $this->assertEquals('For now', $this->factory('milestone', [
            'GET /milestones/' . TPM_MILESTONE_ID => true
        ])->get(TPM_MILESTONE_ID)->title);
    }

    /**
     * @dataProvider provider
     */
    public function testUpdate(array $data): void
    {
        $data['id'] = TPM_MILESTONE_ID;
        $data['title'] = 'Updated Milestone Title';
        $this->assertTrue($this->factory('milestone', [
            'PUT /milestones/' . TPM_MILESTONE_ID => true
        ])->update($data));
    }

    /**
     * @test
     */
    public function complete(): void
    {
        $this->assertTrue($this->factory('milestone', [
            'PUT /milestones/' . TPM_MILESTONE_ID . '/complete' => true
        ])->complete(TPM_MILESTONE_ID));
    }

    /**
     * @test
     */
    public function unComplete(): void
    {
        $this->assertTrue($this->factory('milestone', [
            'PUT /milestones/' . TPM_MILESTONE_ID . '/uncomplete' => true
        ])->unComplete(TPM_MILESTONE_ID));
    }

    public function testDelete(): void
    {
        $this->assertTrue($this->factory('milestone', [
            'DELETE /milestones/' . TPM_MILESTONE_ID => true
        ])->delete(TPM_MILESTONE_ID));
    }

    public function provider()
    {
        return [
            [
                [
                    'title' => 'Test milestone',
                    'description' => 'Bla, Bla, Bla',
                    'deadline' => '20241231',
                    'responsible_party_ids' => TPM_USER_ID,
                    'notify' => false,
                    'reminder' => false,
                    'private' => false,
                ],
            ],
        ];
    }
}
