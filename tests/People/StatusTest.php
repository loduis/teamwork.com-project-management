<?php

namespace TeamWorkPm\Tests\People;

use TeamWorkPm\Tests\TestCase;

final class StatusTest extends TestCase
{
    /**
     * @dataProvider provider
     */
    public function testCreate(array $data): void
    {
        $data['person_id'] = TPM_USER_ID;
        $this->assertEquals(TPM_TEST_ID, $this->factory('people.status', [
            'POST /people/' . TPM_USER_ID . '/status' => fn($data) => $this->assertMatchesJsonSnapshot($data)
        ])->create($data));
    }

    public function testAll(): void
    {
        $this->assertNotEmpty($this->factory('people.status', [
            'GET /statuses' => true
        ])->all());
    }

    public function testGet(): void
    {
        $this->assertEquals(
            'test2',
            $this->factory('people.status', [
                'GET /people/' . TPM_USER_ID . '/status' => true
            ])->get(TPM_USER_ID)->status
        );
    }

    public function testUpdate(): void
    {
        $this->assertTrue($this->factory('people.status', [
            'PUT /people/' . TPM_USER_ID . '/status/' . TPM_ME_STATUS_ID => true
        ])->save([
            'person_id' => TPM_USER_ID,
            'id' => TPM_ME_STATUS_ID,
            'status' => 'test3',
            'notify' => false,
        ]));

        $this->assertTrue($this->factory('people.status', [
            'PUT /people/status/' . TPM_ME_STATUS_ID => true
        ])->save([
            'id' => TPM_ME_STATUS_ID,
            'status' => 'test3',
            'notify' => false,
        ]));

    }

    public function testDelete(): void
    {
        $this->assertTrue($this->factory('people.status', [
            'DELETE /people/' . TPM_USER_ID . '/status/' . TPM_ME_STATUS_ID => true
        ])->delete(TPM_ME_STATUS_ID, TPM_USER_ID));

        $this->assertTrue($this->factory('people.status', [
            'DELETE /people/status/' . TPM_ME_STATUS_ID => true
        ])->delete(TPM_ME_STATUS_ID));
    }

    public function provider()
    {
        return [
            [
                [
                    'status' => 'Test people status',
                    'notify' => false,
                ],
            ],
        ];
    }
}
