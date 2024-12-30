<?php

namespace TeamWorkPm\Tests;

final class TimeTest extends TestCase
{
    /**
     * @dataProvider provider
     */
    public function testCreate1(array $data): void
    {
        $data['project_id'] = TPM_PROJECT_ID_1;
        $this->assertEquals(TPM_TEST_ID, $this->factory('time', [
            sprintf('POST /projects/%s/time_entries', TPM_PROJECT_ID_1) => fn($data) => $this->assertMatchesJsonSnapshot($data)
        ])->create($data));
    }

    /**
     * @dataProvider provider
     */
    public function testCreate2(array $data): void
    {
        $data['task_id'] = TPM_TASK_ID;
        $this->assertEquals(TPM_TEST_ID, $this->factory('time', [
            sprintf('POST /tasks/%s/time_entries', TPM_TASK_ID) => fn($data) => $this->assertMatchesJsonSnapshot($data)
        ])->create($data));
    }

    public function testAll(): void
    {
        $this->assertGreaterThan(0, count($this->factory('time', [
            'GET /time_entries' => true
        ])->all()));
    }

    public function testGet(): void
    {
        $this->assertEquals(1, $this->factory('time', [
            'GET /time_entries/' . TPM_TIME_ID_1 => true
        ])->get(TPM_TIME_ID_1)->hours);
    }

    public function testGetByProject(): void
    {
        $this->assertGreaterThan(0, count($this->factory('time', [
            'GET /projects/' . TPM_PROJECT_ID_1 . '/time_entries' => true
        ])->getByProject(TPM_PROJECT_ID_1)));
    }

    public function testGetByTask(): void
    {
        $this->assertGreaterThan(0, count($this->factory('time', [
            'GET /tasks/' . TPM_TASK_ID . '/time_entries' => true
        ])->getByTask(TPM_TASK_ID)));
    }

    public function testGetTotal(): void
    {
        $this->assertEquals(1060, $this->factory('time', [
            'GET /time_entries/total' => true
        ])->getTotal()->totalMinsSum);
    }

    public function testGetEstimated(): void
    {
        $this->assertEquals(15, $this->factory('time', [
            'GET /projects/estimatedtime/total' => true
        ])->getEstimated()[0]->totalEstimatedMins);
    }

    public function testUpdate(): void
    {
        $this->assertTrue($this->factory('time', [
            'PUT /time_entries/' . TPM_TIME_ID_1 => true
        ])->update(['id'  => TPM_TIME_ID_1, 'description' => 'Test Time Updated']));
    }

    public function testDelete(): void
    {
        $this->assertTrue($this->factory('time', [
            'DELETE /time_entries/' . TPM_TIME_ID_1 => true
        ])->delete(TPM_TIME_ID_1));
    }

    public function provider()
    {
        return [
            [
                [
                    'description' => 'Test Time',
                    'person_id' => null, // this is a required field
                    'date' => 20241223,
                    'hours' => 5,
                    'minutes' => 30,
                    'time' => '08:30',
                    'is_billable' => true,
                ],
            ],
        ];
    }
}
