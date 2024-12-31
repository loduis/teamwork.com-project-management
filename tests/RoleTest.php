<?php

namespace TeamWorkPm\Tests;

final class RoleTest extends TestCase
{
    /**
     * @dataProvider provider
     */
    public function testCreate(array $data): void
    {
        $data['project_id'] = TPM_PROJECT_ID_1;
        $this->assertEquals(TPM_TEST_ID, $this->factory('role', [
            'POST /projects/' . TPM_PROJECT_ID_1 . '/roles' => fn($data) => $this->assertMatchesJsonSnapshot($data)
        ])->create($data));
    }

    public function testGet(): void
    {
        $this->assertEquals('Test 3', $this->factory('role', [
            'GET /roles/' . TPM_ROLE_ID => true
        ])->get(TPM_ROLE_ID)->name);
    }

    public function testAll(): void
    {
        $this->assertGreaterThan(0,
            count($this->factory('role', [
                'GET /projects/' . TPM_PROJECT_ID_2 . '/roles' => true
            ])->all(TPM_PROJECT_ID_2))
        );
    }

    /**
     * @dataProvider provider
     */
    public function testUpdate(array $data): void
    {
        $data['id'] = TPM_ROLE_ID;
        $data['name'] = 'Updated Role';
        $data['description'] = 'Updated description for testing unit tests';
        $this->assertEquals(TPM_TEST_ID, $this->factory('role', [
            'PUT /roles/' . TPM_ROLE_ID => fn($data) => $this->assertMatchesJsonSnapshot($data)
        ])->update($data));
    }

    public function testDelete(): void
    {
        $this->assertEquals(TPM_TEST_ID, $this->factory('role', [
            'DELETE /roles/' . TPM_ROLE_ID => true
        ])->delete(TPM_ROLE_ID));
    }

    public function provider()
    {
        return [
            [
                [
                    'name' => 'Test Role',
                    'description' => 'Test role for testing unit tests',
                    'users' => TPM_USER_ID,
                ],
            ],
        ];
    }
}
