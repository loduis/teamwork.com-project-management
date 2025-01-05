<?php

namespace TeamWorkPm\Tests;

final class TeamTest extends TestCase
{
    /**
    * @dataProvider provider
    */
    public function testCreate(array $data): void
    {
      $this->assertEquals(TPM_TEST_ID, $this->factory('team', [
           'POST /teams' => fn($data) => $this->assertMatchesJsonSnapshot($data)
        ])->create($data));
    }

    public function testAll(): void
    {
        $this->assertGreaterThan(0, count($this->factory('team', [
           'GET /teams' => true
        ])->all()));
    }

    public function testGet(): void
    {
        $this->assertEquals('Draft', $this->factory('team', [
           'GET /teams/' . TPM_TEAM_ID => true
        ])->get(TPM_TEAM_ID)->name);
    }

    /**
    * @dataProvider provider
    */
    public function testUpdate(array $data): void
    {
        $data['id'] = TPM_TEAM_ID;
        $data['name'] = 'Updated Team Name';
        $this->assertTrue($this->factory('team', [
            'PUT /teams/' . TPM_TEAM_ID => true
        ])->update($data));
    }

    public function testDelete(): void
    {
        $this->assertTrue($this->factory('team', [
            'DELETE /teams/' . TPM_TEAM_ID => true
        ])->delete(TPM_TEAM_ID));
    }

    public function provider(): array
    {
        return [
            [
                [
                    'name' => 'Test Team',
                    'description' => 'Description of Test Team',
                    'team_type' => 'Draft'
                ],
            ],
        ];
    }
}