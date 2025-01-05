<?php

namespace TeamWorkPm\Tests\Portfolio;

use TeamWorkPm\Tests\TestCase;

final class CardTest extends TestCase
{
    public function testCreate(): void
    {
        $this->assertEquals(TPM_TEST_ID, $this->factory('portfolio.card', [
            'POST /portfolio/columns/' . TPM_PORTFOLIO_COLUMN_ID . '/cards' => fn($data) => $this->assertMatchesJsonSnapshot($data)
        ])->create(TPM_PORTFOLIO_COLUMN_ID, TPM_PROJECT_ID_1));
    }

    public function testGetByColumn(): void
    {
        $this->assertGreaterThan(0, count($this->factory('portfolio.card', [
            'GET /portfolio/columns/' . TPM_PORTFOLIO_COLUMN_ID . '/cards' => true
        ])->getByColumn(TPM_PORTFOLIO_COLUMN_ID)));
    }

    public function testMove(): void
    {
        $this->assertTrue($this->factory('portfolio.card', [
            'PUT /portfolio/cards/' . TPM_PORTFOLIO_CARD_ID . '/move' => true
        ]
            )->move(
                TPM_PORTFOLIO_CARD_ID,
                TPM_PORTFOLIO_COLUMN_ID,
                TPM_PORTFOLIO_COLUMN_ID_2,
                3 // after position
            )
        );
    }

    public function testGet(): void
    {
        $this->assertEquals('Project Board', $this->factory('portfolio.card', [
            'GET /portfolio/cards/' . TPM_PORTFOLIO_CARD_ID => true
        ])->get(TPM_PORTFOLIO_CARD_ID)->name);
    }

    public function testDelete(): void
    {
        $this->assertTrue($this->factory('portfolio.card', [
            'DELETE /portfolio/cards/' . TPM_PORTFOLIO_CARD_ID => true
        ])->delete(TPM_PORTFOLIO_CARD_ID));
    }
}
