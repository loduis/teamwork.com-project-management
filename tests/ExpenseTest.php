<?php

namespace TeamWorkPm\Tests;

final class ExpenseTest extends TestCase
{
    /**
    * @dataProvider provider
    */
    public function testCreate(array $data): void
    {
      $this->assertEquals(TPM_TEST_ID, $this->factory('expense', [
           'POST /expenses' => fn($data) => $this->assertMatchesJsonSnapshot($data)
        ])->create($data));
    }

    public function testAll(): void
    {
        $this->assertGreaterThan(0, count($this->factory('expense', [
           'GET /expenses' => true
        ])->all()));
    }

    public function testGetByProject(): void
    {
        $this->assertGreaterThan(0, count($this->factory('expense', [
           'GET /projects/' . TPM_PROJECT_ID_1 . '/expenses' => true
        ])->getByProject(TPM_PROJECT_ID_1)));
    }

    public function testGet(): void
    {
        $this->assertEquals('test', $this->factory('expense', [
           'GET /expenses/' . TPM_EXPENSE_ID => true
        ])->get(TPM_EXPENSE_ID)->name);
    }

    /**
    * @dataProvider provider
    */
    public function testUpdate(array $data): void
    {
        $data['id'] = TPM_EXPENSE_ID;
        $data['name'] = 'Updated Expense Name';
        $this->assertTrue($this->factory('expense', [
            'PUT /expenses/' . TPM_EXPENSE_ID => true
        ])->update($data));
    }

    public function testDelete(): void
    {
        $this->assertTrue($this->factory('expense', [
            'DELETE /expenses/' . TPM_EXPENSE_ID => true
        ])->delete(TPM_EXPENSE_ID));
    }

    public function provider()
    {
        return [
            [
                [
                    'name' => 'Test expense',
                    'description' => 'Bla, Bla, Bla',
                    'cost' => 100,
                    'project_id' => TPM_PROJECT_ID_1,
                    'date' => '20250101'
                ],
            ],
        ];
    }
}
