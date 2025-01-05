<?php

namespace TeamWorkPm\Tests\Me;

use TeamWorkPm\Tests\TestCase;

final class StatusTest extends TestCase
{
    /**
     * @dataProvider provider
     */
    public function testCreate(array $data): void
    {
        $this->assertEquals(TPM_TEST_ID, $this->factory('me.status', [
            'POST /me/status' => fn($data) => $this->assertMatchesJsonSnapshot($data)
        ])->create($data));
    }

    public function testGet(): void
    {
        $this->assertEquals('test2', $this->factory('me.status', [
            'GET /me/status' => true
        ])->get()->status);
    }

    /**
     * @dataProvider provider
     */
    public function testUpdate(array $data): void
    {
        $data['status'] = 'Test me status updated';
        $data['id'] = TPM_ME_STATUS_ID;

        $this->assertTrue($this->factory('me.status', [
            'PUT /me/status/' . TPM_ME_STATUS_ID => fn($data) => $this->assertMatchesJsonSnapshot($data)
        ])->update($data));
    }

    public function testDelete(): void
    {
        $this->assertTrue($this->factory('me.status', [
            'DELETE /me/status/' . TPM_ME_STATUS_ID => true
        ])->delete(TPM_ME_STATUS_ID));
    }

    public function provider()
    {
        return [
            [
                [
                    'status' => 'Test me status',
                    'notify' => false,
                ],
            ],
        ];
    }
}
