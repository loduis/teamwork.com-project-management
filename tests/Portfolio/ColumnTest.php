<?php

namespace TeamWorkPm\Tests\Portfolio;

use TeamWorkPm\Tests\TestCase;

final class ColumnTest extends TestCase
{
    /**
    * @dataProvider provider
    */
    public function testCreate(array $data): void
    {
        $data['board_id'] = TPM_PORTFOLIO_BOARD_ID;

        $this->assertEquals(TPM_TEST_ID, $this->factory('portfolio.column', [
           'POST /portfolio/boards/' . TPM_PORTFOLIO_BOARD_ID . '/columns' => fn($data) => $this->assertMatchesJsonSnapshot($data)
        ])->create($data));
    }

    public function testGetByBoard(): void
    {
        $this->assertGreaterThan(0, count($this->factory('portfolio.column', [
           'GET /portfolio/boards/' . TPM_PORTFOLIO_BOARD_ID . '/columns' => true
        ])->getByBoard(TPM_PORTFOLIO_BOARD_ID)));
    }

    public function testGet(): void
    {
        $this->assertEquals('manner', $this->factory('portfolio.column', [
           'GET /portfolio/columns/' . TPM_PORTFOLIO_COLUMN_ID => true
        ])->get(TPM_PORTFOLIO_COLUMN_ID)->name);
    }

    /**
    * @dataProvider provider
    */
    public function testUpdate(array $data): void
    {
        $data['id'] = TPM_PORTFOLIO_COLUMN_ID;
        $data['name'] = 'Updated Board Name';
        $this->assertTrue($this->factory('portfolio.column', [
            'PUT /portfolio/columns/' . TPM_PORTFOLIO_COLUMN_ID => true
        ])->update($data));
    }

    public function testDelete(): void
    {
        $this->assertTrue($this->factory('portfolio.column', [
            'DELETE /portfolio/columns/' . TPM_PORTFOLIO_COLUMN_ID => true
        ])->delete(TPM_PORTFOLIO_COLUMN_ID));
    }

    public function provider()
    {
        return [
            [
                [
                    'name' => 'test board',
                    'color' => '#cccccc',
                ],
            ],
        ];
    }
}