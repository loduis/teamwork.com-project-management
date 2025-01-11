<?php

namespace TeamWorkPm\Tests;

final class InvoiceTest extends TestCase
{
    /**
    * @dataProvider provider
    */
    public function testCreate(array $data): void
    {
      $this->assertEquals(TPM_TEST_ID, $this->factory('invoice', [
           'POST /projects/' . TPM_PROJECT_ID_1 . '/invoices' => fn($data) => $this->assertMatchesJsonSnapshot($data)
        ])->create($data));
    }

    public function testAll(): void
    {
        $this->assertGreaterThan(0, count($this->factory('invoice', [
           'GET /invoices' => true
        ])->all()));
    }

    public function testGetByProject(): void
    {
        $this->assertGreaterThan(0, count($this->factory('invoice', [
           'GET /projects/' . TPM_PROJECT_ID_1 . '/invoices' => true
        ])->getByProject(TPM_PROJECT_ID_1)));
    }

    public function testGet(): void
    {
        $this->assertEquals('USD', $this->factory('invoice', [
           'GET /invoices/' . TPM_INVOICE_ID => true
        ])->get(TPM_INVOICE_ID)->currencyCode);
    }

    /**
    * @dataProvider provider
    */
    public function testUpdate(array $data): void
    {
        $data['id'] = TPM_INVOICE_ID;
        $data['description'] = 'Updated Invoice Description';
        $this->assertTrue($this->factory('invoice', [
            'PUT /invoices/' . TPM_INVOICE_ID => true
        ])->update($data));
    }

    public function testComplete(): void
    {
        $this->assertTrue($this->factory('invoice', [
            'PUT /invoices/' . TPM_INVOICE_ID . '/complete' => true
        ])->complete(TPM_INVOICE_ID));
    }

    public function testUnComplete(): void
    {
        $this->assertTrue($this->factory('invoice', [
            'PUT /invoices/' . TPM_INVOICE_ID . '/uncomplete' => true
        ])->unComplete(TPM_INVOICE_ID));
    }

    /**
     * @todo This test fail on real api
     *
     * @return void
     */
    public function testAddTime(): void
    {
        $this->assertTrue($this->factory('invoice', [
            'PUT /invoices/' . TPM_INVOICE_ID . '/lineitems' => true
        ])->addTime(TPM_INVOICE_ID, '10 hours'));
    }

    public function testDelete(): void
    {
        $this->assertTrue($this->factory('invoice', [
            'DELETE /invoices/' . TPM_INVOICE_ID => true
        ])->delete(TPM_INVOICE_ID));
    }

    public function provider()
    {
        return [
            [
                [
                    'description' => 'Bla, Bla, Bla',
                    'number' => 100,
                    'project_id' => TPM_PROJECT_ID_1,
                    'display_date' => '20250101'
                ],
            ],
        ];
    }
}
