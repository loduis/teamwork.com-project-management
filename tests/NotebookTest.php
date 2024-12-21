<?php

namespace TeamWorkPm\Tests;

final class NotebookTest extends TestCase
{
    /**
     * @dataProvider provider
     */
    public function testCreate(array $data): void
    {
        $data['project_id'] = TPM_PROJECT_ID_1;
        $this->assertEquals(TPM_TEST_ID, $this->factory('notebook', [
            'POST /projects/' . TPM_PROJECT_ID_1 . '/notebooks' => fn($data) => $this->assertMatchesJsonSnapshot($data)
        ])->create($data));
    }

    public function testGet(): void
    {
        $this->assertEquals('This is a test', $this->factory('notebook', [
            'GET /notebooks/' . TPM_NOTEBOOK_ID => true
        ])->get(TPM_NOTEBOOK_ID)->name);
    }

    public function testGetByProject(): void
    {
        $this->assertGreaterThan(0, count($this->factory('notebook', [
            'GET /projects/' . TPM_PROJECT_ID_1 . '/notebooks' => true
        ])->getByProject(TPM_PROJECT_ID_1)));
    }

    public function testLock(): void
    {
        $this->assertTrue($this->factory('notebook', [
            'PUT /notebooks/' . TPM_NOTEBOOK_ID . '/lock' => true
        ])->lock(TPM_NOTEBOOK_ID));
    }

    public function testUnlock(): void
    {
        $this->assertTrue($this->factory('notebook', [
            'PUT /notebooks/' . TPM_NOTEBOOK_ID . '/unlock' => true
        ])->unlock(TPM_NOTEBOOK_ID));
    }

    /**
     * @dataProvider provider
     */
    public function testUpdate(array $data): void
    {
        $data['id'] = TPM_NOTEBOOK_ID;
        $data['name'] = 'Updated Notebook Name';
        $this->assertTrue($this->factory('notebook', [
            'PUT /notebooks/' . TPM_NOTEBOOK_ID => true
        ])->update($data));
    }

    public function testAll(): void
    {
        $this->assertGreaterThan(0, count($this->factory('notebook', [
            'GET /notebooks' => true
        ])->all()));
    }

    public function testCopy(): void
    {
        $this->assertEquals(TPM_TEST_ID, $this->factory('notebook', [
            'PUT /notebooks/' . TPM_NOTEBOOK_ID . '/copy' =>  function () {
                return '{"STATUS":"OK","id": ' . TPM_TEST_ID . '}';
            }
        ])->copy(TPM_NOTEBOOK_ID, TPM_PROJECT_ID_2));
    }

    public function testMove(): void
    {
        $this->assertTrue($this->factory('notebook', [
            'PUT /notebooks/' . TPM_NOTEBOOK_ID . '/move' => true
        ])->move(TPM_NOTEBOOK_ID, TPM_PROJECT_ID_2));
    }

    public function testDelete(): void
    {
        $this->assertTrue($this->factory('notebook', [
            'DELETE /notebooks/' . TPM_NOTEBOOK_ID => true
        ])->delete(TPM_NOTEBOOK_ID));
    }

    public function provider()
    {
        return [
            [
                [
                    'name' => 'Test notebook',
                    'description' => 'Bla, Bla, Bla',
                    'content' => '<b>Nada</b>, <i>nada</i>, nada',
                    'notify' => false,
                    'category_id' => 0,
                    'category_name' => 'New Notebook category.',
                    'private' => false,
                ],
            ],
        ];
    }
}
