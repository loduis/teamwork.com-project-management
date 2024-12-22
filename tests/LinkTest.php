<?php

namespace TeamWorkPm\Tests;

final class LinkTest extends TestCase
{
    /**
     * @dataProvider provider
     */
    public function testCreate(array $data): void
    {
        $data['project_id'] = TPM_PROJECT_ID_1;
        $this->assertEquals(TPM_TEST_ID, $this->factory('link', [
            'POST /projects/' . TPM_PROJECT_ID_1 . '/links' => fn($data) => $this->assertMatchesJsonSnapshot($data)
        ])->create($data));
    }

    public function testGet(): void
    {
        $this->assertEquals('http://developer.teamworkpm.net', $this->factory('link', [
            'GET /links/' . TPM_LINK_ID => true
        ])->get(TPM_LINK_ID)->code);
    }

    public function testGetByProject(): void
    {
        $this->assertGreaterThan(0, count($this->factory('link', [
            'GET /projects/' . TPM_PROJECT_ID_1 . '/links' => true
        ])->getByProject(TPM_PROJECT_ID_1)));
    }

    /**
     * @dataProvider provider
     */
    public function testUpdate(array $data): void
    {
        $data['id'] = TPM_LINK_ID;
        $data['name'] = 'Updated Name';
        $this->assertTrue($this->factory('link', [
            'PUT /links/' . TPM_LINK_ID => true
        ])->update($data));
    }

    public function testAll(): void
    {
        $this->assertGreaterThan(0, count($this->factory('link', [
            'GET /links' => true
        ])->all()));
    }

    public function testDelete(): void
    {
        $this->assertTrue($this->factory('link', [
            'DELETE /links/' . TPM_LINK_ID => true
        ])->delete(TPM_LINK_ID));
    }


    public function provider()
    {
        return [
            [
                [
                    'name' => 'Test Link',
                    'description' => 'Bla, Bla, Bla',
                    'code' => 'http://developer.teamworkpm.net',
                    'height' => 300,
                    'width' => 300,
                    'private' => false,
                    'notify' => null
                ],
            ],
        ];
    }
}
