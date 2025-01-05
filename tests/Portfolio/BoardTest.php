<?php

namespace TeamWorkPm\Tests\Portfolio;

use TeamWorkPm\Tests\TestCase;

/**
 * @todo Need real test data
 */
final class BoardTest extends TestCase
{
    /**
    * @dataProvider provider
    */
    public function testCreate(array $data): void
    {
      $this->assertEquals(TPM_TEST_ID, $this->factory('portfolio.board', [
           'POST /portfolio/boards' => fn($data) => $this->assertMatchesJsonSnapshot($data)
        ])->create($data));
    }

    public function testAll(): void
    {
        $this->assertGreaterThan(0, count($this->factory('portfolio.board', [
           'GET /portfolio/boards' => true
        ])->all()));
    }

    public function testGet(): void
    {
        $this->assertEquals('test board', $this->factory('portfolio.board', [
           'GET /portfolio/boards/' . TPM_PORTFOLIO_BOARD_ID => true
        ])->get(TPM_PORTFOLIO_BOARD_ID)->name);
    }

    /**
    * @dataProvider provider
    */
    public function testUpdate(array $data): void
    {
        $data['id'] = TPM_PORTFOLIO_BOARD_ID;
        $data['name'] = 'Updated Board Name';
        $this->assertTrue($this->factory('portfolio.board', [
            'PUT /portfolio/boards/' . TPM_PORTFOLIO_BOARD_ID => true
        ])->update($data));
    }

    public function testDelete(): void
    {
        $this->assertTrue($this->factory('portfolio.board', [
            'DELETE /portfolio/boards/' . TPM_PORTFOLIO_BOARD_ID => true
        ])->delete(TPM_PORTFOLIO_BOARD_ID));
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