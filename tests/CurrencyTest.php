<?php

namespace TeamWorkPm\Tests;

final class CurrencyTest extends TestCase
{
    public function testAll(): void
    {
        $this->assertGreaterThan(0, count($this->factory('currency', [
           'GET /currencycodes' => true
        ])->all()));
    }
}